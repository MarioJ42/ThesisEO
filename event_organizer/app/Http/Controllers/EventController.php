<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\WeddingPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $user = Auth::user();

        $query = Event::with(['client', 'package', 'pl'])
            ->when($search, function ($q, $search) {
                return $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('pl', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest();

        if ($user->role === 'pl') {
            $query->where('pl_id', $user->id);
            $events = $query->paginate($perPage)->appends(request()->query());

            $myEvents = $events;
            $plEvents = collect();
            $unassignedEvents = collect();
        } else {
            $events = $query->paginate($perPage)->appends(request()->query());
            $myEvents = $events;
            $plEvents = collect();
            $unassignedEvents = collect();
        }

        $clients = User::where('role', 'klien')->orderBy('name', 'asc')->get();
        $packages = WeddingPackage::orderBy('name', 'asc')->get();
        $projectLeaders = User::whereIn('role', ['pl', 'owner'])->orderBy('name', 'asc')->get();

        return view('events.index', compact('events', 'clients', 'packages', 'projectLeaders', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'event_date' => 'required|date|after:today',
            'package_id' => 'required',
        ]);

        $packageId = $request->package_id === 'custom' ? null : $request->package_id;
        $role = Auth::user()->role;
        $status = ($role === 'owner' || $role === 'pl') ? 'planning' : 'draft';
        $plId = ($role === 'owner') ? Auth::id() : (($role === 'pl') ? Auth::id() : null);

        $event = Event::create([
            'client_id' => $request->client_id,
            'pl_id' => $plId,
            'title' => $request->title,
            'event_date' => $request->event_date,
            'package_id' => $packageId,
            'status' => $status,
        ]);

        if ($packageId) {
            $templates = DB::table('package_templates')->where('package_id', $packageId)->get();
            $slots = [];

            foreach ($templates as $template) {
                $slots[] = [
                    'event_id' => $event->id,
                    'vendor_category_id' => $template->vendor_category_id,
                    'vendor_id' => null,
                    'vendor_contact_id' => null,
                    'vendor_package_id' => null,
                    'session' => $template->session,
                    'role_detail' => $template->role_detail,
                    'is_included' => $template->is_included,
                    'status' => 'unassigned',
                    'deal_price' => 0,
                    'meal_crew' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($slots)) {
                DB::table('event_vendor')->insert($slots);
            }
        }

        return redirect()->back()->with('success', 'New Event Arrangement created & Vendor Slots generated!');
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'pl_id' => 'nullable|exists:users,id',
            'status' => 'required|in:draft,planning,ongoing,completed,canceled',
        ]);

        $event->pl_id = $request->pl_id;
        $event->status = $request->status;

        if ($event->pl_id && $event->status === 'draft') {
            $event->status = 'planning';
        }

        $event->save();

        return redirect()->back()->with('success', 'Event successfully updated!');
    }

    public function manage(Event $event)
    {
        $user = Auth::user();

        if ($user->role === 'pl' && $event->pl_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $event->load(['client', 'pl', 'package']);

        $slots = DB::table('event_vendor')
            ->join('vendor_categories', 'event_vendor.vendor_category_id', '=', 'vendor_categories.id')
            ->leftJoin('vendors', 'event_vendor.vendor_id', '=', 'vendors.id')
            ->leftJoin('vendor_contacts', 'event_vendor.vendor_contact_id', '=', 'vendor_contacts.id')
            ->where('event_vendor.event_id', $event->id)
            ->select(
                'event_vendor.*',
                'vendor_categories.name as category_name',
                'vendors.name as vendor_name',
                'vendor_contacts.name as contact_name',
                'vendor_contacts.phone as contact_phone'
            )
            ->orderBy('event_vendor.id')
            ->get();

        $morningSlots = $slots->where('session', 'morning');
        $eveningSlots = $slots->where('session', 'evening');
        $verifiedSlots = $slots->where('status', 'verified');

        $categories = \App\Models\VendorCategory::with('vendors')->get();

        $allowedVendors = collect();
        if ($event->package_id) {
            $allowedVendors = DB::table('package_vendor_pivot')
                ->where('package_id', $event->package_id)
                ->get()
                ->groupBy('vendor_category_id');
        }

        return view('events.manage', compact(
            'event',
            'user',
            'slots',
            'morningSlots',
            'eveningSlots',
            'verifiedSlots',
            'categories',
            'allowedVendors'
        ));
    }

    public function addCustomSlot(Request $request, Event $event)
    {
        $request->validate([
            'session' => 'required|in:morning,evening,all_day',
            'vendor_category_id' => 'required|exists:vendor_categories,id',
            'role_detail' => 'required|string|max:255',
        ]);

        DB::table('event_vendor')->insert([
            'event_id' => $event->id,
            'vendor_category_id' => $request->vendor_category_id,
            'session' => $request->session,
            'role_detail' => $request->role_detail,
            'is_included' => false,
            'status' => 'unassigned',
            'deal_price' => 0,
            'meal_crew' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Custom slot added successfully!');
    }

    public function assignVendorToSlot(Request $request, Event $event, $slotId)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id'
        ]);

        $contactId = DB::table('vendor_contacts')
            ->where('vendor_id', $request->vendor_id)
            ->value('id');

        DB::table('event_vendor')
            ->where('id', $slotId)
            ->where('event_id', $event->id)
            ->update([
                'vendor_id' => $request->vendor_id,
                'vendor_contact_id' => $contactId,
                'status' => 'reviewing'
            ]);

        return redirect()->back()->with('success', 'Vendor successfully assigned to slot!');
    }

    public function removeVendorFromSlot(Event $event, $slotId)
    {
        DB::table('event_vendor')
            ->where('id', $slotId)
            ->where('event_id', $event->id)
            ->update([
                'vendor_id' => null,
                'vendor_contact_id' => null,
                'status' => 'unassigned'
            ]);

        return redirect()->back()->with('success', 'Vendor removed from slot.');
    }

    public function updateSlotStatus(Request $request, Event $event, $slotId)
    {
        $request->validate([
            'status' => 'required|in:unassigned,reviewing,verified,rejected,signed'
        ]);

        DB::table('event_vendor')
            ->where('id', $slotId)
            ->where('event_id', $event->id)
            ->update([
                'status' => $request->status,
                'meal_crew' => $request->meal_crew ?? 0
            ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Status and meal crew updated successfully.']);
        }

        return redirect()->back()->with('success', 'Slot status and meal crew updated.');
    }
}
