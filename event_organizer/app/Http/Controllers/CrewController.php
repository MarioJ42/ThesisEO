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
            ->whereIn('events.status', ['planning', 'ongoing'])
            ->select('event_crew.*', 'events.title', 'events.event_date', 'events.venue', 'events.status as event_status')
            ->orderBy('events.event_date', 'asc')
            ->get();

        $historyEvents = DB::table('event_crew')
            ->join('events', 'event_crew.event_id', '=', 'events.id')
            ->where('event_crew.user_id', $user->id)
            ->where('event_crew.status', 'Verified')
            ->where('events.status', 'completed')
            ->select('event_crew.*', 'events.title', 'events.event_date', 'events.venue', 'events.status as event_status')
            ->orderBy('events.event_date', 'desc')
            ->get();

        $pendingRequests = DB::table('event_crew')
            ->join('events', 'event_crew.event_id', '=', 'events.id')
            ->where('event_crew.user_id', $user->id)
            ->where('event_crew.status', 'Requested')
            ->select('event_crew.*', 'events.title', 'events.event_date')
            ->orderBy('events.event_date', 'asc')
            ->get();

        $availableEvents = DB::table('events')
            ->whereIn('status', ['planning', 'ongoing'])
            ->where('event_date', '>=', Carbon::now()->toDateString())
            ->where('event_date', '<=', Carbon::now()->addDays(14)->toDateString())
            ->whereNotIn('id', function ($query) use ($user) {
                $query->select('event_id')->from('event_crew')->where('user_id', $user->id);
            })
            ->orderBy('event_date', 'asc')
            ->get();

        return view('crew.dashboard', compact('myEvents', 'pendingRequests', 'availableEvents', 'historyEvents'));
    }

    public function applyEvent(Request $request, $eventId)
    {
        DB::table('event_crew')->insert([
            'event_id' => $eventId,
            'user_id' => Auth::id(),
            'jobdesk' => null,
            'session' => 'reception',
            'status' => 'Requested',
            'fee' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Application sent successfully! Please wait for PL assignment.');
    }
}
