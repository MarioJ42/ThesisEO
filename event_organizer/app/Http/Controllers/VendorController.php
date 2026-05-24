<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function show($id)
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'owner') return redirect()->route('owner.dashboard');
            if (Auth::user()->role === 'pl') return redirect()->route('pl.dashboard');
            if (Auth::user()->role === 'crew_eo') return redirect()->route('crew.dashboard');
        }

        $vendor = Vendor::with(['categories', 'packages', 'portfolios'])->findOrFail($id);

        $clientEvents = collect();
        if (Auth::check() && Auth::user()->role === 'klien') {
            $clientEvents = Event::where('client_id', Auth::id())
                ->where('status', 'Planning')->get();
        }

        return view('vendor_detail', compact('vendor', 'clientEvents'));
    }

    public function getCalendarEvents($id)
    {
        $slots = DB::table('event_vendor')
            ->join('events', 'event_vendor.event_id', '=', 'events.id')
            ->where('event_vendor.vendor_id', $id)
            ->whereIn('event_vendor.status', ['verified', 'signed'])
            ->select('events.event_date', 'event_vendor.session')
            ->get();

        $calendarData = [];

        foreach ($slots as $slot) {
            $sessionTitle = $slot->session === 'morning' ? 'Morning Session' : 'Reception';

            $calendarData[] = [
                'title' => 'Booked: ' . $sessionTitle,
                'start' => $slot->event_date,
                'allDay' => true,
                'color' => $slot->session === 'morning' ? '#f59e0b' : '#3b82f6',
                'textColor' => '#ffffff'
            ];
        }

        return response()->json($calendarData);
    }
}
