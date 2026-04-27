<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CrewController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $myEvents = DB::table('event_crew')
            ->join('events', 'event_crew.event_id', '=', 'events.id')
            ->where('event_crew.user_id', $user->id)
            ->where('event_crew.status', 'Verified')
            ->select('event_crew.*', 'events.title', 'events.event_date', 'events.venue')
            ->orderBy('events.event_date', 'asc')
            ->get();

        $pendingRequests = DB::table('event_crew')
            ->join('events', 'event_crew.event_id', '=', 'events.id')
            ->where('event_crew.user_id', $user->id)
            ->where('event_crew.status', 'Requested')
            ->select('event_crew.*', 'events.title', 'events.event_date')
            ->orderBy('events.event_date', 'asc')
            ->get();

        $vacantSlots = DB::table('event_crew')
            ->join('events', 'event_crew.event_id', '=', 'events.id')
            ->where('event_crew.status', 'Vacant')
            ->whereIn('events.status', ['planning', 'ongoing'])
            ->where('events.event_date', '>=', Carbon::now()->toDateString())
            ->where('events.event_date', '<=', Carbon::now()->addDays(14)->toDateString())
            ->select('event_crew.*', 'events.title', 'events.event_date')
            ->orderBy('events.event_date', 'asc')
            ->get();

        return view('crew.dashboard', compact('myEvents', 'pendingRequests', 'vacantSlots'));
    }

    public function applyJob(Request $request, $slotId)
    {
        $updated = DB::table('event_crew')
            ->where('id', $slotId)
            ->where('status', 'Vacant')
            ->update([
                'user_id' => Auth::id(),
                'status' => 'Requested',
                'updated_at' => now()
            ]);

        if ($updated) {
            return redirect()->back()->with('success', 'Application sent successfully! Please wait for PL approval.');
        }

        return redirect()->back()->with('error', 'Sorry, this slot has already been taken by someone else.');
    }
}
