<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\WeddingPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $plId = ($role === 'pl') ? Auth::id() : null;

        if ($role === 'owner') {
            $plId = Auth::id();
        }

        Event::create([
            'client_id' => $request->client_id,
            'pl_id' => $plId,
            'title' => $request->title,
            'event_date' => $request->event_date,
            'package_id' => $packageId,
            'status' => $status,
        ]);

        return redirect()->back()->with('success', 'New Event Arrangement successfully created!');
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'pl_id' => 'nullable|exists:users,id',
        ]);

        $event->pl_id = $request->pl_id;

        if ($event->pl_id && $event->status === 'draft') {
            $event->status = 'planning';
        }

        $event->save();

        return redirect()->back()->with('success', 'Project Leader successfully assigned and event updated!');
    }
}
