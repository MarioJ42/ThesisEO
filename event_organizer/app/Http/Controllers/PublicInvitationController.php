<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class PublicInvitationController extends Controller
{
    public function show($token)
    {
        $guest = DB::table('guests')
            ->join('events', 'guests.event_id', '=', 'events.id')
            ->select('guests.*', 'events.title as event_title', 'events.event_date')
            ->where('guests.barcode_token', $token)
            ->first();

        if (!$guest) {
            abort(404, 'Invitation not found or invalid token.');
        }

        return view('invitation.show', compact('guest'));
    }

    public function rsvp(Request $request, $token)
    {
        $request->validate([
            'status' => 'required|in:attending,not_attending'
        ]);

        DB::table('guests')
            ->where('barcode_token', $token)
            ->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);

        $msg = $request->status === 'attending'
            ? 'Thank you! Your attendance confirmation has been saved.'
            : 'Thank you for your confirmation.';

        // Kembalikan ke halaman sebelumnya dan tanamkan Cookie selama 30 hari agar form tidak muncul lagi
        return redirect()->back()
            ->with('success', $msg)
            ->withCookie(cookie('rsvp_' . $token, true, 60 * 24 * 30));
    }
}
