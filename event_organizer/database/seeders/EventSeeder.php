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
            [
                'id' => 1,
                'client_id' => 3,
                'pl_id' => 2,
                'package_id' => 1,
                'title' => 'Kezia & Clarin',
                'event_date' => Carbon::now()->addMonths(3)->toDateString(),
                'venue' => 'Pakuwon Imperial Ballroom',
                'total_price' => 190000000,
                'status' => 'planning',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('events')->insert($events);

        $eventVendor = [
            [
                'event_id' => 1,
                'vendor_id' => 1,
                'vendor_contact_id' => 1,
                'vendor_package_id' => 1,
                'deal_price' => 25000000,
                'status' => 'verified',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'event_id' => 1,
                'vendor_id' => 2,
                'vendor_contact_id' => 2,
                'vendor_package_id' => 2,
                'deal_price' => 150000000,
                'status' => 'signed',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('event_vendor')->insert($eventVendor);
    }
}
