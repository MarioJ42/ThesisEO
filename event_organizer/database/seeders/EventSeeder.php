<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('events')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $events = [
            // 1. Klien: Jessica Christy (ID: 2) | Status: Completed
            [
                'client_id'   => 2,
                'pl_id'       => 1,
                'package_id'  => 3,
                'title'       => 'Wedding of Yoshua & Jessica',
                'event_date'  => '2026-06-02',
                'venue'       => 'DoubleTree by Hilton Surabaya',
                'cover_image' => 'event_covers/yoshua_jessica.jpg',
                'total_price' => 166500000.00,
                'status'      => 'completed',
                'created_at'  => Carbon::parse('2026-05-16')->subMonths(6),
                'updated_at'  => Carbon::parse('2026-05-16')->subDays(1),
            ],

            // 2. Klien: Januar Andika (ID: 3) | Status: Planning
            [
                'client_id'   => 3,
                'pl_id'       => 1,
                'package_id'  => 2,
                'title'       => 'Wedding of Januar & Riska',
                'event_date'  => '2026-06-21',
                'venue'       => 'Santika Premiere Ballroom',
                'cover_image' => 'event_covers/januar_riska.jpg',
                'total_price' => 147000000.00,
                'status'      => 'planning',
                'created_at'  => Carbon::now()->subMonths(4),
                'updated_at'  => Carbon::now(),
            ],

            // 3. Klien: Syea Pascha (ID: 4) | Status: Planning
            [
                'client_id'   => 4,
                'pl_id'       => 1,
                'package_id'  => 4,
                'title'       => 'Wedding of Daniel & Syea',
                'event_date'  => '2026-06-27',
                'venue'       => 'Zhang Palace',
                'cover_image' => 'event_covers/daniel_syea.jpg',
                'total_price' => 191250000.00,
                'status'      => 'planning',
                'created_at'  => Carbon::now()->subMonths(2),
                'updated_at'  => Carbon::now(),
            ],

            // 4. Klien: Febriani Ruth (ID: 5) | Status: Planning
            [
                'client_id'   => 5,
                'pl_id'       => 1,
                'package_id'  => 1,
                'title'       => 'Wedding of Kevin & Febriani',
                'event_date'  => '2026-07-04',
                'venue'       => 'Casa Milieu',
                'cover_image' => 'event_covers/kevin_febriani.jpg',
                'total_price' => 62250000.00,
                'status'      => 'planning',
                'created_at'  => Carbon::now()->subDays(15),
                'updated_at'  => Carbon::now(),
            ],

            // 5. Klien: Dennis Bastian (ID: 8) | Status: Planning
            [
                'client_id'   => 8,
                'pl_id'       => 1,
                'package_id'  => 3,
                'title'       => 'Wedding of Edward & Anne',
                'event_date'  => '2026-07-05',
                'venue'       => 'XO Palace',
                'cover_image' => 'event_covers/edward_anne.jpg',
                'total_price' => 149000000.00,
                'status'      => 'planning',
                'created_at'  => Carbon::now()->subDays(5),
                'updated_at'  => Carbon::now(),
            ],

            // 6. Klien: Rika Alicia (ID: 6) | Status: Draft
            [
                'client_id'   => 6,
                'pl_id'       => null,
                'package_id'  => null,
                'title'       => 'Wedding of Michael & Rika',
                'event_date'  => '2026-08-15',
                'venue'       => 'The Socialite',
                'cover_image' => null,
                'total_price' => null,
                'status'      => 'draft',
                'created_at'  => Carbon::now()->subDays(2),
                'updated_at'  => Carbon::now(),
            ],
        ];

        DB::table('events')->insert($events);
    }
}
