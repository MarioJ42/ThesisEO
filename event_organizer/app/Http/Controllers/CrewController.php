<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CrewController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $myEvents = DB::table('event_crew')
            ->join('events', 'event_crew.event_id', '=', 'events.id')
            ->where('event_crew.user_id', $user->id)
            ->where('event_crew.assignment_status', 'approved')
            ->select('event_crew.*', 'events.title', 'events.event_date', 'events.venue')
            ->get();

        $pendingRequests = DB::table('event_crew')
            ->join('events', 'event_crew.event_id', '=', 'events.id')
            ->where('event_crew.user_id', $user->id)
            ->where('event_crew.assignment_status', 'requested')
            ->select('event_crew.*', 'events.title', 'events.event_date')
            ->get();

        $vacantSlots = DB::table('event_crew')
            ->join('events', 'event_crew.event_id', '=', 'events.id')
            ->whereNull('event_crew.user_id')
            ->where('event_crew.assignment_status', 'vacant')
            ->whereIn('events.status', ['planning', 'ongoing'])
            ->select('event_crew.*', 'events.title', 'events.event_date')
            ->orderBy('events.event_date', 'asc')
            ->get();

        return view('crew.dashboard', compact('myEvents', 'pendingRequests', 'vacantSlots'));
    }

    public function applyJob(Request $request, $slotId)
    {
        DB::table('event_crew')->where('id', $slotId)->whereNull('user_id')->update([
            'user_id' => Auth::id(),
            'assignment_status' => 'requested',
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Job Application Sent! Waiting for Project Leader approval.');
    }
}
