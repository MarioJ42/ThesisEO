<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Masukkan data ke tabel events
        $events = [
            [
                'id' => 1,
                'client_id' => 3, // Kezia
                'pl_id' => 2, // Mario
                'package_id' => 1,
                'title' => 'Kezia & Sharon',
                'event_date' => Carbon::now()->addMonths(3)->toDateString(),
                'venue' => 'Pakuwon Imperial Ballroom',
                'total_price' => 190000000,
                'status' => 'planning', // Sesuai enum
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('events')->insert($events);

        // 2. Masukkan data ke tabel pivot event_vendor
        $eventVendor = [
            [
                'event_id' => 1,
                'vendor_id' => 1,
                'vendor_contact_id' => 1,
                'vendor_package_id' => 1,
                'deal_price' => 25000000,
                'status' => 'verified', // Sesuai enum
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'event_id' => 1,
                'vendor_id' => 2,
                'vendor_contact_id' => 2,
                'vendor_package_id' => 2,
                'deal_price' => 150000000,
                'status' => 'signed', // Sesuai enum
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('event_vendor')->insert($eventVendor);
    }
}
