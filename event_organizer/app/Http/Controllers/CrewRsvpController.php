<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CrewRsvpController extends Controller
{
    private function authorizeAccess(Event $event)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['crew_eo', 'pl', 'owner'])) {
            abort(403, 'UNAUTHORIZED ACCESS.');
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
            abort(403, 'DIGITAL GUESTBOOK REQUIRES FENIX EO VENDOR TO BE VERIFIED.');
        }

        if ($user->role === 'crew_eo') {
            if (!\Carbon\Carbon::parse($event->event_date)->isToday()) {
                abort(403, 'RSVP SYSTEM IS ONLY ACCESSIBLE ON THE DAY OF THE EVENT.');
            }

            $crewAssignments = DB::table('event_crew')
                ->where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->get();

            if ($crewAssignments->isEmpty()) {
                abort(403, 'YOU ARE NOT ASSIGNED TO THIS EVENT.');
            }

            $isAllowed = false;
            foreach ($crewAssignments as $assignment) {
                $jobdesk = strtolower(trim($assignment->jobdesk));
                if (str_contains($jobdesk, 'fd') || str_contains($jobdesk, 'front desk') || str_contains($jobdesk, 'usher') || $jobdesk === 'pl') {
                    $isAllowed = true;
                    break;
                }
            }

            if (!$isAllowed) {
                abort(403, 'YOUR JOBDESK DOES NOT HAVE ACCESS TO THE RSVP SYSTEM.');
            }
        }
    }

    public function hub(Event $event)
    {
        $this->authorizeAccess($event);
        return view('crew.rsvp.hub', compact('event'));
    }

    public function scan(Event $event)
    {
        $this->authorizeAccess($event);
        return view('crew.rsvp.scan', compact('event'));
    }

    public function search(Request $request, Event $event)
    {
        $this->authorizeAccess($event);

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

    public function createGuest(Event $event)
    {
        $this->authorizeAccess($event);
        return view('crew.rsvp.create_guest', compact('event'));
    }

    public function storeGuest(Request $request, Event $event)
    {
        $this->authorizeAccess($event);

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

        return redirect()->route('crew.rsvp.checkin.form', [$event->id, $token])
            ->with('success', 'Guest added! Please complete the check-in.');
    }

    public function checkInForm(Event $event, $token)
    {
        $this->authorizeAccess($event);

        $guest = DB::table('guests')->where('barcode_token', $token)->first();

        if (!$guest) {
            abort(404, 'Guest QR Code not found in the database.');
        }

        $allGuests = DB::table('guests')
            ->where('event_id', $event->id)
            ->where('id', '!=', $guest->id)
            ->select('id', 'name', 'phone_number')
            ->get();

        $existingTitipan = DB::table('guests')
            ->where('titipan_by', $guest->id)
            ->select('id', 'name', 'phone_number', 'angpao_count as qty')
            ->get();

        return view('crew.rsvp.checkin_form', compact('event', 'guest', 'allGuests', 'existingTitipan'));
    }

    public function processCheckIn(Request $request, Event $event, $guestId)
    {
        $this->authorizeAccess($event);

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

            DB::table('guests')->where('titipan_by', $guestId)->update([
                'angpao_count' => 0,
                'angpao_titipan' => false,
                'titipan_by' => null,
            ]);

            if ($request->filled('titipan_data')) {
                $titipanArray = json_decode($request->titipan_data, true);

                if (is_array($titipanArray) && count($titipanArray) > 0) {
                    foreach ($titipanArray as $titipan) {
                        DB::table('guests')->where('id', $titipan['id'])->update([
                            'status' => 'not_attending',
                            'angpao_count' => intval($titipan['qty']),
                            'angpao_type' => $request->angpao_type,
                            'angpao_titipan' => true,
                            'titipan_by' => $guestId,
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('crew.rsvp.checkin.summary', [$event->id, $guestId])
                ->with('success_checkin', 'Guest successfully checked in!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to check-in guest: ' . $e->getMessage()]);
        }
    }

    public function checkInSummary(Event $event, $guestId)
    {
        $this->authorizeAccess($event);

        $guest = DB::table('guests')->where('id', $guestId)->first();

        if (!$guest) {
            abort(404, 'Guest not found.');
        }

        return view('crew.rsvp.summary', compact('event', 'guest'));
    }

    public function syncOfflineData(Request $request, Event $event)
    {
        $this->authorizeAccess($event);

        $payload = json_decode($request->getContent(), true);
        if (!is_array($payload)) {
            return response()->json(['success' => false, 'message' => 'Invalid data format']);
        }

        $checkins = $payload['checkins'] ?? [];
        $newGuests = $payload['new_guests'] ?? [];

        if (empty($checkins) && empty($newGuests)) {
            return response()->json(['success' => false, 'message' => 'No data to sync']);
        }

        DB::beginTransaction();
        try {
            $syncedNewGuests = 0;
            foreach ($newGuests as $guest) {
                $token = Str::random(10);
                while (DB::table('guests')->where('barcode_token', $token)->exists()) {
                    $token = Str::random(10);
                }

                DB::table('guests')->insert([
                    'event_id' => $event->id,
                    'name' => $guest['name'],
                    'phone_number' => $guest['phone_number'],
                    'pax_invited' => $guest['pax_invited'],
                    'table_name' => $guest['table_name'],
                    'side' => $guest['side'] ?? 'General',
                    'status' => 'attending',
                    'barcode_token' => $token,
                    'created_at' => \Carbon\Carbon::parse($guest['timestamp'])->format('Y-m-d H:i:s'),
                    'updated_at' => now(),
                ]);
                $syncedNewGuests++;
            }

            $syncedCheckins = 0;
            foreach ($checkins as $data) {
                DB::table('guests')->where('id', $data['guest_id'])->update([
                    'status' => 'checked_in',
                    'check_in_time' => \Carbon\Carbon::parse($data['timestamp'])->format('Y-m-d H:i:s'),
                    'pax_actual' => $data['pax_actual'],
                    'angpao_type' => $data['angpao_type'],
                    'angpao_count' => $data['angpao_count'],
                    'updated_at' => now(),
                ]);

                DB::table('guests')->where('titipan_by', $data['guest_id'])->update([
                    'angpao_count' => 0,
                    'angpao_titipan' => false,
                    'titipan_by' => null,
                ]);

                if (!empty($data['titipan_data'])) {
                    foreach ($data['titipan_data'] as $titipan) {
                        DB::table('guests')->where('id', $titipan['id'])->update([
                            'status' => 'not_attending',
                            'angpao_count' => intval($titipan['qty']),
                            'angpao_type' => $data['angpao_type'],
                            'angpao_titipan' => true,
                            'titipan_by' => $data['guest_id'],
                            'updated_at' => now(),
                        ]);
                    }
                }
                $syncedCheckins++;
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'synced_checkins' => $syncedCheckins,
                'synced_new_guests' => $syncedNewGuests
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
