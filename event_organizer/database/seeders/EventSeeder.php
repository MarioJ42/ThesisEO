<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            // 1. Klien: Jessica Christy (ID: 2) | Status: Completed
            [
                'client_id'   => 2,
                'pl_id'       => 1, // Di-handle oleh Fenix
                'package_id'  => 3, // Full Day Wedding
                'title'       => 'Wedding of Yoshua & Jessica',
                'event_date'  => Carbon::now()->subDays(2)->format('Y-m-d'), // Baru saja selesai
                'venue'       => 'DoubleTree by Hilton Surabaya',
                'cover_image' => 'event_covers/yoshua_jessica.jpg',
                'total_price' => 166500000.00,
                'status'      => 'completed',
                'created_at'  => Carbon::now()->subMonths(6),
                'updated_at'  => Carbon::now()->subDays(1),
            ],

            // 2. Klien: Januar Andika (ID: 3) | Status: Ongoing
            [
                'client_id'   => 3,
                'pl_id'       => 1,
                'package_id'  => 2, // Half Day Wedding
                'title'       => 'Wedding of Januar & Riska',
                'event_date'  => Carbon::now()->format('Y-m-d'), // Acara hari ini
                'venue'       => 'Santika Premiere Ballroom',
                'cover_image' => 'event_covers/januar_riska.jpg',
                'total_price' => 147000000.00,
                'status'      => 'ongoing',
                'created_at'  => Carbon::now()->subMonths(4),
                'updated_at'  => Carbon::now(),
            ],

            // 3. Klien: Syea Pascha (ID: 4) | Status: Planning
            [
                'client_id'   => 4,
                'pl_id'       => null, // PL belum di-assign (bagus untuk test UI Admin)
                'package_id'  => 4, // Dreamy Full Day Wedding
                'title'       => 'Wedding of Daniel & Syea',
                'event_date'  => Carbon::now()->addMonths(2)->format('Y-m-d'),
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
                'package_id'  => 1, // Holy Matrimony
                'title'       => 'Wedding of Kevin & Febriani',
                'event_date'  => Carbon::now()->addMonths(4)->format('Y-m-d'),
                'venue'       => 'Casa Milieu',
                'cover_image' => 'event_covers/kevin_febriani.jpg',
                'total_price' => 62250000.00,
                'status'      => 'planning',
                'created_at'  => Carbon::now()->subDays(15),
                'updated_at'  => Carbon::now(),
            ],

            // 5. Klien: Rika Alicia (ID: 6) | Status: Draft
            [
                'client_id'   => 6,
                'pl_id'       => null,
                'package_id'  => null, // Custom (klien pilih sendiri tanpa pakem)
                'title'       => 'Wedding of Michael & Rika',
                'event_date'  => Carbon::now()->addMonths(6)->format('Y-m-d'),
                'venue'       => 'The Socialite',
                'cover_image' => null, // Belum upload cover
                'total_price' => null, // Harga belum deal
                'status'      => 'draft',
                'created_at'  => Carbon::now()->subDays(2),
                'updated_at'  => Carbon::now(),
            ],

            // 6. Klien: Dennis Bastian (ID: 8) | Status: Planning
            [
                'client_id'   => 8,
                'pl_id'       => 1,
                'package_id'  => 3, // Full Day Wedding
                'title'       => 'Wedding of Edward & Anne (#addictEDtoANNE)',
                'event_date'  => Carbon::now()->addMonths(8)->format('Y-m-d'),
                'venue'       => 'XO Palace',
                'cover_image' => 'event_covers/edward_anne.jpg',
                'total_price' => 149000000.00,
                'status'      => 'planning',
                'created_at'  => Carbon::now()->subDays(5),
                'updated_at'  => Carbon::now(),
            ],
        ];

        DB::table('events')->insert($events);

    }
}
