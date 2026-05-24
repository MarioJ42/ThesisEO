<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\WeddingPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $user = Auth::user();

        if ($user->role === 'klien') {
            $events = Event::with(['package', 'pl'])
                ->where('client_id', $user->id)
                ->when($search, function ($q, $search) {
                    return $q->where('title', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate($perPage)->appends(request()->query());

            $packages = WeddingPackage::where('is_active', true)->orderBy('base_price', 'asc')->get();

            return view('client.events', compact('events', 'packages', 'user'));
        }

        $query = Event::with(['client', 'package', 'pl']);

        if ($user->role === 'pl') {
            $query->where('pl_id', $user->id)
                ->where('status', '!=', 'draft');
        }

        $query->when($search, function ($q, $search) {
            return $q->where(function ($subQuery) use ($search) {
                $subQuery->where('title', 'like', "%{$search}%")
                    ->orWhereHas('pl', function ($plQuery) use ($search) {
                        $plQuery->where('name', 'like', "%{$search}%");
                    });
            });
        })
            ->latest();

        $events = $query->paginate($perPage)->appends(request()->query());
        $clients = User::where('role', 'klien')->orderBy('name', 'asc')->get();
        $packages = WeddingPackage::orderBy('name', 'asc')->get();
        $projectLeaders = User::whereIn('role', ['pl', 'owner'])->orderBy('name', 'asc')->get();

        return view('events.index', compact('events', 'clients', 'packages', 'projectLeaders', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255|unique:events,title',
            'event_date' => 'required|date|after:today',
            'package_id' => 'required',
        ]);

        $packageId = $request->package_id === 'custom' ? null : $request->package_id;
        $role = Auth::user()->role;
        $status = ($role === 'owner' || $role === 'pl') ? 'planning' : 'draft';
        $plId = ($role === 'owner' || $role === 'pl') ? Auth::id() : null;

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
                    'pic_name' => null,
                    'pic_phone' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($slots)) {
                DB::table('event_vendor')->insert($slots);
            }
        }

        $routePrefix = ($role === 'klien') ? 'client' : $role;
        return redirect()->route($routePrefix . '.events.manage', $event->id)
            ->with('success', 'New Event Arrangement created! You can now plan the vendors.');
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

        if ($user->role === 'klien' && $event->client_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $event->load(['client', 'pl', 'package']);

        $slots = DB::table('event_vendor')
            ->join('vendor_categories', 'event_vendor.vendor_category_id', '=', 'vendor_categories.id')
            ->leftJoin('vendors', 'event_vendor.vendor_id', '=', 'vendors.id')
            ->leftJoin('vendor_contacts', 'event_vendor.vendor_contact_id', '=', 'vendor_contacts.id')
            ->leftJoin('vendor_packages', 'event_vendor.vendor_package_id', '=', 'vendor_packages.id')
            ->where('event_vendor.event_id', $event->id)
            ->select(
                'event_vendor.*',
                'vendor_categories.name as category_name',
                'vendors.name as vendor_name',
                'vendor_contacts.name as contact_name',
                'vendor_contacts.phone as contact_phone',
                'vendor_packages.name as package_name'
            )
            ->orderBy('event_vendor.id')
            ->get();

        $morningSlots = $slots->where('session', 'morning');
        $eveningSlots = $slots->where('session', 'evening');
        $verifiedSlots = $slots->whereIn('status', ['verified', 'signed']);

        $categories = \App\Models\VendorCategory::with('vendors')->orderBy('name', 'asc')->get();

        $allowedVendors = collect();
        $baseCosts = [];

        if ($event->package_id) {
            $allowedVendors = DB::table('package_vendor_pivot')
                ->where('package_id', $event->package_id)
                ->get()
                ->groupBy('vendor_category_id');

            $templateCategories = DB::table('package_templates')
                ->where('package_id', $event->package_id)
                ->where('is_included', true)
                ->pluck('vendor_category_id')
                ->toArray();

            foreach ($templateCategories as $catId) {
                $allowedIds = collect($allowedVendors[$catId] ?? [])->pluck('vendor_id');
                $minPrice = DB::table('vendor_packages')
                    ->whereIn('vendor_id', $allowedIds)
                    ->where('vendor_category_id', $catId)
                    ->min('price');
                $baseCosts[$catId] = $minPrice ?? 0;
            }
        }

        $assignedVendorIds = $slots->whereNotNull('vendor_id')->pluck('vendor_id')->unique();
        $vendorContacts = DB::table('vendor_contacts')
            ->whereIn('vendor_id', $assignedVendorIds)
            ->get()
            ->groupBy('vendor_id');

        $vendorPackages = DB::table('vendor_packages')
            ->whereIn('vendor_id', $assignedVendorIds)
            ->get()
            ->groupBy('vendor_id');

        $statusFilter = request('guest_status');
        $sort = request('guest_sort', 'newest');

        $guestsQuery = DB::table('guests')->where('event_id', $event->id);

        if ($statusFilter && $statusFilter !== 'all') {
            $guestsQuery->where('status', $statusFilter);
        }

        if ($sort === 'oldest') {
            $guestsQuery->orderBy('id', 'asc');
        } elseif ($sort === 'name_asc') {
            $guestsQuery->orderBy('name', 'asc');
        } elseif ($sort === 'name_desc') {
            $guestsQuery->orderBy('name', 'desc');
        } else {
            $guestsQuery->orderBy('id', 'desc');
        }

        $guests = $guestsQuery->get();

        $crewSlots = DB::table('event_crew')
            ->leftJoin('users', 'event_crew.user_id', '=', 'users.id')
            ->where('event_crew.event_id', $event->id)
            ->whereNotNull('event_crew.jobdesk')
            ->select('event_crew.*', 'users.name as crew_name', 'users.phone as crew_phone')
            ->orderBy('event_crew.session')
            ->orderBy('event_crew.id')
            ->get();

        $applicants = DB::table('event_crew')
            ->join('users', 'event_crew.user_id', '=', 'users.id')
            ->where('event_crew.event_id', $event->id)
            ->whereIn('event_crew.status', ['Requested', 'Verified'])
            ->select('users.id as user_id', 'users.name', 'users.phone')
            ->distinct()
            ->get();

        if ($user->role === 'klien') {
            $hiddenCategories = [
                'Robe & Veil',
                'Tie',
                'Meal Crew',
                'Headpiece',
                'Ring Box',
                'Wedding Car',
                'Baloon & Dove'
            ];

            $clientSlots = $slots->filter(function ($slot) use ($hiddenCategories) {
                return !in_array(strtolower(trim($slot->category_name)), array_map('strtolower', $hiddenCategories));
            });

            $clientMorningSlots = $clientSlots->where('session', 'morning');
            $clientEveningSlots = $clientSlots->where('session', 'evening');

            return view('client.manage', [
                'event' => $event,
                'user' => $user,
                'slots' => $clientSlots,
                'morningSlots' => $clientMorningSlots,
                'eveningSlots' => $clientEveningSlots,
                'categories' => $categories,
                'allowedVendors' => $allowedVendors,
                'vendorPackages' => $vendorPackages,
                'baseCosts' => $baseCosts
            ]);
        }

        return view('events.manage', compact(
            'event',
            'user',
            'slots',
            'morningSlots',
            'eveningSlots',
            'verifiedSlots',
            'categories',
            'allowedVendors',
            'vendorContacts',
            'vendorPackages',
            'baseCosts',
            'guests',
            'crewSlots',
            'applicants'
        ));
    }

    public function addCustomSlot(Request $request, Event $event)
    {
        $request->validate([
            'session' => 'required|in:morning,evening,all_day',
            'vendor_category_id' => 'required|exists:vendor_categories,id',
            'role_detail' => 'nullable|string|max:255',
        ]);

        DB::table('event_vendor')->insert([
            'event_id' => $event->id,
            'vendor_category_id' => $request->vendor_category_id,
            'session' => $request->session,
            'role_detail' => $request->role_detail ?? '-',
            'is_included' => false,
            'status' => 'unassigned',
            'deal_price' => 0,
            'meal_crew' => 0,
            'pic_name' => null,
            'pic_phone' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Custom slot added successfully!');
    }

    public function assignVendorToSlot(Request $request, Event $event, $slotId)
    {
        if ($request->has('vendor_id')) {
            $request->validate(['vendor_id' => 'required|exists:vendors,id']);

            $contact = DB::table('vendor_contacts')
                ->where('vendor_id', $request->vendor_id)
                ->where('is_primary', true)
                ->first();

            if (!$contact) {
                $contact = DB::table('vendor_contacts')->where('vendor_id', $request->vendor_id)->first();
            }

            DB::table('event_vendor')
                ->where('id', $slotId)
                ->where('event_id', $event->id)
                ->update([
                    'vendor_id' => $request->vendor_id,
                    'vendor_contact_id' => $contact ? $contact->id : null,
                    'pic_name' => $contact ? $contact->name : null,
                    'pic_phone' => $contact ? $contact->phone : null,
                    'vendor_package_id' => null,
                    'deal_price' => 0,
                    'status' => 'reviewing',
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Vendor selected! Please choose the package.');
        }

        if ($request->has('vendor_package_id')) {
            $request->validate(['vendor_package_id' => 'required|exists:vendor_packages,id']);

            $package = DB::table('vendor_packages')->where('id', $request->vendor_package_id)->first();

            DB::table('event_vendor')
                ->where('id', $slotId)
                ->where('event_id', $event->id)
                ->update([
                    'vendor_package_id' => $package->id,
                    'deal_price' => $package->price,
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Package successfully assigned!');
        }

        return redirect()->back()->with('error', 'Invalid request.');
    }

    public function removeVendorFromSlot(Event $event, $slotId)
    {
        DB::table('event_vendor')
            ->where('id', $slotId)
            ->where('event_id', $event->id)
            ->update([
                'vendor_id' => null,
                'vendor_contact_id' => null,
                'vendor_package_id' => null,
                'status' => 'unassigned',
                'deal_price' => 0,
                'meal_crew' => 0,
                'pic_name' => null,
                'pic_phone' => null,
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('error', 'Vendor removed from slot.');
    }

    public function destroySlot(Event $event, $slotId)
    {
        DB::table('event_vendor')
            ->where('id', $slotId)
            ->where('event_id', $event->id)
            ->delete();

        return redirect()->back()->with('error', 'Vendor slot deleted successfully!');
    }

    public function updateSlotStatus(Request $request, Event $event, $slotId)
    {
        $request->validate([
            'status' => 'required|in:unassigned,reviewing,verified,rejected,signed',
            'vendor_contact_id' => 'nullable|exists:vendor_contacts,id'
        ]);

        $updateData = [
            'status' => $request->status,
            'meal_crew' => $request->meal_crew ?? 0,
            'vendor_contact_id' => $request->vendor_contact_id,
            'updated_at' => now(),
        ];

        if ($request->filled('vendor_contact_id')) {
            $contact = DB::table('vendor_contacts')->where('id', $request->vendor_contact_id)->first();
            if ($contact) {
                $updateData['pic_name'] = $contact->name;
                $updateData['pic_phone'] = $contact->phone;
            }
        }

        DB::table('event_vendor')
            ->where('id', $slotId)
            ->where('event_id', $event->id)
            ->update($updateData);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => "Vendor's status updated"]);
        }

        return redirect()->back()->with('success', "Vendor's status updated");
    }

    public function updateDealPrice(Request $request, Event $event, $slotId)
    {
        $request->validate([
            'deal_price' => 'required|numeric|min:0',
            'net_price' => 'nullable|numeric|min:0',
        ]);

        DB::table('event_vendor')
            ->where('id', $slotId)
            ->where('event_id', $event->id)
            ->update([
                'deal_price' => $request->deal_price,
                'net_price' => $request->net_price ?? 0,
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Deal price successfully negotiated & updated!');
    }

    public function addPackageFromVendor(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'vendor_id' => 'required|exists:vendors,id',
            'package_id' => 'required|exists:vendor_packages,id',
            'category_id' => 'required',
            'session' => 'required|string',
        ]);

        $event = \App\Models\Event::where('id', $request->event_id)
            ->where('client_id', \Illuminate\Support\Facades\Auth::id())
            ->firstOrFail();

        $package = DB::table('vendor_packages')->where('id', $request->package_id)->first();

        $contact = DB::table('vendor_contacts')
            ->where('vendor_id', $request->vendor_id)
            ->where('is_primary', true)
            ->first();

        if (!$contact) {
            $contact = DB::table('vendor_contacts')->where('vendor_id', $request->vendor_id)->first();
        }

        $event->vendors()->attach($request->vendor_id, [
            'vendor_category_id' => $request->category_id,
            'vendor_package_id' => $request->package_id,
            'vendor_contact_id' => $contact ? $contact->id : null,
            'pic_name' => $contact ? $contact->name : null,
            'pic_phone' => $contact ? $contact->phone : null,
            'session' => $request->session,
            'deal_price' => $package ? $package->price : 0,
            'is_included' => 0,
            'status' => 'reviewing',
            'role_detail' => '-',
            'meal_crew' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('client.events.manage', $event->id)
            ->with('success', 'Package successfully added! Waiting for verification.');
    }

    public function storeGuest(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:50',
            'pax_invited' => 'required|integer|min:1',
            'table_name' => 'nullable|string|max:50',
            'side' => 'nullable|in:Groom,Bride,General',
        ]);

        $token = Str::random(10);
        while (DB::table('guests')->where('barcode_token', $token)->exists()) {
            $token = Str::random(10);
        }

        DB::table('guests')->insert([
            'event_id' => $event->id,
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'pax_invited' => $request->pax_invited,
            'table_name' => $request->table_name,
            'side' => $request->side ?? 'General',
            'status' => 'attending',
            'barcode_token' => $token,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Guest added successfully as Attending!')->with('active_tab', 'rsvp');
    }

    public function updateGuest(Request $request, Event $event, $guestId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:50',
            'pax_invited' => 'required|integer|min:1',
            'table_name' => 'nullable|string|max:50',
            'side' => 'nullable|in:Groom,Bride,General',
            'status' => 'required|in:pending,attending,not_attending,checked_in',
            'pax_actual' => 'nullable|integer|min:0',
            'angpao_count' => 'nullable|integer|min:0',
            'angpao_type' => 'nullable|in:fisik,digital',
        ]);

        DB::beginTransaction();
        try {
            DB::table('guests')->where('id', $guestId)->where('event_id', $event->id)->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'pax_invited' => $request->pax_invited,
                'table_name' => $request->table_name,
                'side' => $request->side ?? 'General',
                'status' => $request->status,
                'pax_actual' => $request->pax_actual ?? 0,
                'angpao_count' => $request->angpao_count ?? 0,
                'angpao_type' => $request->angpao_type ?? 'fisik',
                'updated_at' => now(),
            ]);

            if ($request->filled('titipan_data')) {
                $titipanArray = json_decode($request->titipan_data, true);

                if (is_array($titipanArray) && count($titipanArray) > 0) {
                    foreach ($titipanArray as $titipan) {
                        DB::table('guests')->where('id', $titipan['id'])->update([
                            'angpao_count' => DB::raw("COALESCE(angpao_count, 0) + " . intval($titipan['qty'])),
                            'angpao_type' => $request->angpao_type ?? 'fisik',
                            'angpao_titipan' => true,
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Guest details updated successfully!')->with('active_tab', 'rsvp');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update guest details: ' . $e->getMessage())->with('active_tab', 'rsvp');
        }
    }

    public function destroyGuest(Event $event, $guestId)
    {
        DB::table('guests')->where('id', $guestId)->where('event_id', $event->id)->delete();
        return redirect()->back()->with('success', 'Guest successfully removed!')->with('active_tab', 'rsvp');
    }

    public function guestbook(Event $event)
    {
        $user = Auth::user();

        if ($user->role === 'klien' && $event->client_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $hasFenixGuestbook = DB::table('event_vendor')
            ->leftJoin('vendor_categories', 'event_vendor.vendor_category_id', '=', 'vendor_categories.id')
            ->leftJoin('vendors', 'event_vendor.vendor_id', '=', 'vendors.id')
            ->where('event_vendor.event_id', $event->id)
            ->where('vendor_categories.name', 'like', '%Guest Book%')
            ->where('vendors.name', 'like', '%Fenix EO%')
            ->whereIn('event_vendor.status', ['verified', 'signed'])
            ->exists();

        if (!$hasFenixGuestbook) {
            return redirect()->route('client.events.manage', $event->id)->with('error', 'Digital Guestbook feature requires Fenix EO vendor to be verified first.');
        }

        $event->load(['client', 'package']);

        $statusFilter = request('guest_status');
        $sort = request('guest_sort', 'newest');

        $guestsQuery = DB::table('guests')->where('event_id', $event->id);

        if ($statusFilter && $statusFilter !== 'all') {
            $guestsQuery->where('status', $statusFilter);
        }

        if ($sort === 'oldest') {
            $guestsQuery->orderBy('id', 'asc');
        } elseif ($sort === 'name_asc') {
            $guestsQuery->orderBy('name', 'asc');
        } elseif ($sort === 'name_desc') {
            $guestsQuery->orderBy('name', 'desc');
        } else {
            $guestsQuery->orderBy('id', 'desc');
        }

        $guests = $guestsQuery->get();

        return view('client.guestbook', compact('event', 'user', 'guests'));
    }

    public function blastWaReminders(Event $event)
    {
        $user = Auth::user();

        if ($user->role === 'pl' && $event->pl_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $guests = DB::table('guests')
            ->where('event_id', $event->id)
            ->where('status', 'attending')
            ->whereNotNull('phone_number')
            ->where('phone_number', '!=', '')
            ->get();

        if ($guests->isEmpty()) {
            return redirect()->back()
                ->with('error', 'No attending guests with valid WhatsApp numbers found.')
                ->with('active_tab', 'rsvp');
        }

        $count = 0;
        foreach ($guests as $guest) {
            \App\Jobs\SendRsvpReminder::dispatch($guest, $event);
            $count++;
        }

        return redirect()->back()
            ->with('success', "Success! $count messages have been queued and will be sent gradually.")
            ->with('active_tab', 'rsvp');
    }

    public function generateCrewSlots(Event $event)
    {
        $morningJobs = ['PL', 'Groom', 'Bride', 'Family Groom', 'Family Bride', 'Runner', 'Gereja', 'Loading', 'Bridesmaid', 'Groomsman'];
        $receptionJobs = ['VIP', 'Family Groom', 'Family Bride', 'MC', 'MD', 'Lighting', 'Band', 'Effect', 'Banquet', 'Usherettes', 'Leader Area', 'Area 1', 'Area 2', 'FD 1', 'FD 2', 'Teapai'];

        $slots = [];
        foreach ($morningJobs as $job) {
            $slots[] = [
                'event_id' => $event->id,
                'user_id' => null,
                'jobdesk' => $job,
                'session' => 'morning',
                'status' => 'Vacant',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        foreach ($receptionJobs as $job) {
            $slots[] = [
                'event_id' => $event->id,
                'user_id' => null,
                'jobdesk' => $job,
                'session' => 'reception',
                'status' => 'Vacant',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        DB::table('event_crew')->insert($slots);
        return redirect()->back()->with('success', 'Template Jobdesk successfully generated!')->with('active_tab', 'crew');
    }

    public function approveCrew(Request $request, Event $event, $slotId)
    {
        $request->validate([
            'fee' => 'required|numeric',
            'user_id' => 'required|exists:users,id'
        ]);

        DB::table('event_crew')->where('id', $slotId)->update([
            'user_id' => $request->user_id,
            'fee' => $request->fee,
            'status' => 'Verified',
            'updated_at' => now()
        ]);

        DB::table('event_crew')
            ->where('event_id', $event->id)
            ->where('user_id', $request->user_id)
            ->where('status', 'Requested')
            ->delete();

        return redirect()->back()->with('success', 'Crew successfully assigned to Jobdesk!')->with('active_tab', 'crew');
    }

    public function rejectCrew(Event $event, $slotId)
    {
        $slot = DB::table('event_crew')->where('id', $slotId)->first();

        if ($slot && $slot->user_id) {
            $existsInPool = DB::table('event_crew')
                ->where('event_id', $event->id)
                ->where('user_id', $slot->user_id)
                ->where('status', 'Requested')
                ->exists();

            if (!$existsInPool) {
                DB::table('event_crew')->insert([
                    'event_id' => $event->id,
                    'user_id' => $slot->user_id,
                    'jobdesk' => '-',
                    'session' => '-',
                    'status' => 'Requested',
                    'fee' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        DB::table('event_crew')->where('id', $slotId)->update([
            'user_id' => null,
            'fee' => 0,
            'status' => 'Vacant',
            'updated_at' => now()
        ]);

        return redirect()->back()->with('error', 'Crew removed from slot and returned to Applicant Pool.')->with('active_tab', 'crew');
    }

    public function addCustomCrewSlot(Request $request, Event $event)
    {
        $request->validate([
            'jobdesk' => 'required|string|max:255',
            'session' => 'required|in:morning,reception'
        ]);

        DB::table('event_crew')->insert([
            'event_id' => $event->id,
            'user_id' => null,
            'jobdesk' => $request->jobdesk,
            'session' => $request->session,
            'status' => 'Vacant',
            'fee' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Custom Jobdesk successfully added!')->with('active_tab', 'crew');
    }

    public function deleteCrewSlot(Event $event, $slotId)
    {
        DB::table('event_crew')
            ->where('id', $slotId)
            ->where('event_id', $event->id)
            ->delete();

        return redirect()->back()->with('success', 'Jobdesk slot completely deleted!')->with('active_tab', 'crew');
    }

    public function updateSlotDetails(Request $request, Event $event, $slotId)
    {
        $request->validate([
            'pic_name' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:50',
            'meal_crew' => 'nullable|integer|min:0'
        ]);

        DB::table('event_vendor')
            ->where('id', $slotId)
            ->where('event_id', $event->id)
            ->update([
                'pic_name' => $request->pic_name,
                'pic_phone' => $request->pic_phone,
                'meal_crew' => $request->meal_crew ?? 0,
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Operational details (PIC & Meal Crew) updated successfully!');
    }
}
