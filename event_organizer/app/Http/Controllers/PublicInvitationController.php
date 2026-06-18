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
            'status' => 'required|in:attending,not_attending',
            'pax_actual' => 'nullable|integer|min:0'
        ]);

        $updateData = [
            'status' => $request->status,
            'updated_at' => now()
        ];

        if ($request->status === 'attending' && $request->has('pax_actual')) {
            $updateData['pax_actual'] = $request->pax_actual;
        } elseif ($request->status === 'not_attending') {
            $updateData['pax_actual'] = 0;
        }

        DB::table('guests')
            ->where('barcode_token', $token)
            ->update($updateData);

        $msg = $request->status === 'attending'
            ? 'Thank you! Your attendance confirmation has been saved.'
            : 'Thank you for your confirmation.';

        return redirect()->back()
            ->with('success', $msg)
            ->withCookie(cookie('rsvp_' . $token, true, 60 * 24 * 30));
    }
}
