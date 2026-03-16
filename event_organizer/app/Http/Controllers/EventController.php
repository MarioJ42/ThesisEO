<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $events = collect();

        if ($user->role === 'owner') {
            $events = Event::with(['client', 'pl', 'package'])->latest()->get();
            return view('owner.events.index', compact('events'));
        }

        elseif ($user->role === 'pl') {
            $events = Event::with(['client', 'package'])
                ->where('pl_id', $user->id)
                ->latest()
                ->get();
            return view('pl.events.index', compact('events'));
        }

        elseif ($user->role === 'klien') {
            $events = Event::with(['pl', 'package', 'vendors'])
                ->where('client_id', $user->id)
                ->latest()
                ->get();
            return view('client.events.index', compact('events'));
        }

        return redirect('/')->with('error', 'Akses ditolak.');
    }
}
