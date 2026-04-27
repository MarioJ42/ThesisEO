<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CrewRsvpController extends Controller
{
    public function hub(Event $event)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['crew_eo', 'pl', 'owner'])) {
            abort(403, 'Unauthorized access.');
        }

        if ($user->role === 'crew_eo') {
            $crewData = DB::table('event_crew')
                ->where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->first();

            if (!$crewData) {
                abort(403, 'You are not assigned to this event.');
            }

            $allowedJobdesks = ['pl', 'fd 1', 'fd 2', 'usherettes', 'front desk'];
            if (!in_array(strtolower($crewData->jobdesk), $allowedJobdesks)) {
                abort(403, 'Your jobdesk does not have access to the RSVP System.');
            }
        }

        return view('crew.rsvp.hub', compact('event'));
    }

    public function scan(Event $event)
    {
        return view('crew.rsvp.scan', compact('event'));
    }

    public function search(Request $request, Event $event)
    {
        $query = $request->input('query');
        $guests = DB::table('guests')
            ->where('event_id', $event->id)
            ->when($query, function ($q) use ($query) {
                return $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('phone_number', 'LIKE', "%{$query}%");
            })
            ->get();

        return view('crew.rsvp.search', compact('event', 'guests'));
    }

    public function checkInForm(Event $event, $token)
    {
        $guest = DB::table('guests')->where('barcode_token', $token)->first();

        if (!$guest) {
            abort(404, 'Guest QR Code not found in the database.');
        }

        $allGuests = DB::table('guests')
            ->where('event_id', $event->id)
            ->where('id', '!=', $guest->id)
            ->select('id', 'name', 'phone_number')
            ->get();

        return view('crew.rsvp.checkin_form', compact('event', 'guest', 'allGuests'));
    }

    public function processCheckIn(Request $request, Event $event, $guestId)
    {
        $request->validate([
            'pax_actual' => 'required|integer|min:1',
            'angpao_type' => 'required|in:fisik,digital',
            'angpao_count' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            DB::table('guests')->where('id', $guestId)->update([
                'status' => 'checked_in',
                'check_in_time' => now(),
                'pax_actual' => $request->pax_actual,
                'angpao_type' => $request->angpao_type,
                'angpao_count' => $request->angpao_count ?? 0,
                'updated_at' => now(),
            ]);

            if ($request->filled('titipan_data')) {
                $titipanArray = json_decode($request->titipan_data, true);

                if (is_array($titipanArray) && count($titipanArray) > 0) {
                    foreach ($titipanArray as $titipan) {
                        DB::table('guests')->where('id', $titipan['id'])->update([
                            'angpao_count' => DB::raw("COALESCE(angpao_count, 0) + " . intval($titipan['qty'])),
                            'angpao_type' => $request->angpao_type,
                            'angpao_titipan' => true,
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('crew.rsvp.hub', $event->id)
                ->with('success_checkin', 'Guest successfully checked in!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to check-in guest: ' . $e->getMessage()]);
        }
    }
}
