<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GuestSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('guests')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $guests = [];
        $eventId = 1;
        $eventDate = Carbon::parse('2026-05-16 18:00:00');

        $firstNames = ['Budi', 'Hendra', 'Sisca', 'Michael', 'Agus', 'Lina', 'David', 'Fifi', 'Haryanto', 'Yohanes', 'Stefani', 'Kevin', 'William', 'Cynthia', 'Ferry', 'Jimmy', 'Tirta', 'Sugiarto', 'Wati', 'Tony', 'Rudy', 'Lanny', 'Evelyn', 'Daniel', 'Tommy', 'Yenny', 'Ivan', 'Grace', 'Diana'];
        $lastNames = ['Santoso', 'Wijaya', 'Salim', 'Setiawan', 'Hartono', 'Gunawan', 'Halim', 'Kusuma', 'Tanjung', 'Purnomo', 'Widjaja', 'Susanto', 'Pranoto'];

        $namePool = [];
        foreach ($firstNames as $fn) {
            foreach ($lastNames as $ln) {
                if ($fn === 'William' && $ln === 'Susanto') continue;
                if ($fn === 'Merry') continue;
                $namePool[] = [$fn, $ln];
            }
        }
        shuffle($namePool);

        $prefixes = ['0812', '0813', '0821', '0822', '0857', '0818', '0819', '0878', '0896'];
        $sides = ['Groom', 'Bride', 'General'];

        $totalExpected = 0;
        $totalActual = 0;

        $williamId = DB::table('guests')->insertGetId([
            'event_id'      => $eventId,
            'name'          => 'William Susanto',
            'phone_number'  => '082192164686',
            'pax_invited'   => 1,
            'table_name'    => '20',
            'barcode_token' => 'WILSUS20',
            'status'        => 'checked_in',
            'check_in_time' => clone $eventDate->subMinutes(15),
            'pax_actual'    => 1,
            'angpao_count'  => 1,
            'angpao_type'   => 'fisik',
            'angpao_titipan' => 0,
            'titipan_by'    => null,
            'side'          => 'Groom',
            'created_at'    => now(),
            'updated_at'    => now()
        ]);

        $totalExpected += 1;
        $totalActual += 1;

        DB::table('guests')->insert([
            'event_id'      => $eventId,
            'name'          => 'Merry & Partner',
            'phone_number'  => '081234567890',
            'pax_invited'   => 2,
            'table_name'    => '20',
            'barcode_token' => 'MERRYP20',
            'status'        => 'not_attending',
            'check_in_time' => null,
            'pax_actual'    => 0,
            'angpao_count'  => 1,
            'angpao_type'   => 'fisik',
            'angpao_titipan' => 1,
            'titipan_by'    => $williamId,
            'side'          => 'Groom',
            'created_at'    => now(),
            'updated_at'    => now()
        ]);

        $totalExpected += 2;
        $paxInTable20 = 3;

        while ($paxInTable20 < 10) {
            $paxInvited = rand(1, min(4, 10 - $paxInTable20));
            $guests[] = $this->generateRandomGuest($eventId, $eventDate, '20', $paxInvited, $namePool, $prefixes, $sides, $totalActual);
            $totalExpected += $paxInvited;
            $paxInTable20 += $paxInvited;
        }

        for ($table = 2; $table <= 19; $table++) {
            $paxInTable = 0;
            while ($paxInTable < 10) {
                $paxInvited = rand(1, min(4, 10 - $paxInTable));
                $guests[] = $this->generateRandomGuest($eventId, $eventDate, (string)$table, $paxInvited, $namePool, $prefixes, $sides, $totalActual);
                $totalExpected += $paxInvited;
                $paxInTable += $paxInvited;
            }
        }

        foreach (array_chunk($guests, 100) as $chunk) {
            DB::table('guests')->insert($chunk);
        }
    }

    private function generateRandomGuest($eventId, $eventDate, $table, $paxInvited, &$namePool, $prefixes, $sides, &$totalActual)
    {
        if (empty($namePool)) {
            $firstName = 'Guest';
            $lastName = rand(1000, 9999);
        } else {
            $namePair = array_pop($namePool);
            $firstName = $namePair[0];
            $lastName = $namePair[1];
        }

        if ($paxInvited == 1) {
            $name = $firstName . ' ' . $lastName;
        } elseif ($paxInvited == 2) {
            $name = $firstName . ' & Partner';
        } else {
            $name = 'Kel. ' . $firstName . ' ' . $lastName;
        }

        $isCheckedIn = (rand(1, 100) <= 90);
        $paxActual = 0;
        $checkInTime = null;
        $angpaoCount = 0;

        if ($isCheckedIn) {
            $status = 'checked_in';
            $paxActual = (rand(1, 100) <= 5 && $paxInvited > 1) ? $paxInvited - 1 : $paxInvited;
            $totalActual += $paxActual;

            $checkInTime = clone $eventDate;
            $checkInTime->addMinutes(rand(-45, 60));
            $angpaoCount = rand(1, 2);
        } else {
            $status = 'not_attending';
        }

        return [
            'event_id'      => $eventId,
            'name'          => $name,
            'phone_number'  => $prefixes[array_rand($prefixes)] . rand(10000000, 99999999),
            'pax_invited'   => $paxInvited,
            'table_name'    => $table,
            'barcode_token' => strtoupper(Str::random(8)),
            'status'        => $status,
            'check_in_time' => $checkInTime,
            'pax_actual'    => $paxActual,
            'angpao_count'  => $angpaoCount,
            'angpao_type'   => $isCheckedIn ? (rand(1, 10) <= 7 ? 'fisik' : 'digital') : null,
            'angpao_titipan' => 0,
            'titipan_by'    => null,
            'side'          => $sides[array_rand($sides)],
            'created_at'    => now()->subDays(10),
            'updated_at'    => $checkInTime ?? now(),
        ];
    }
}
