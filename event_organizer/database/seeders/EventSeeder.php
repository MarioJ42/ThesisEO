<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $eventId = DB::table('events')->insertGetId([
            'client_id' => 3,
            'pl_id' => 2,
            'package_id' => 3,
            'title' => 'Wedding of Kezia & Clarin',
            'event_date' => '2026-06-19',
            'status' => 'planning',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $templates = DB::table('package_templates')->where('package_id', 3)->get();
        $slots = [];

        foreach ($templates as $template) {
            $vendorId = null;
            $vendorContactId = null;
            $status = 'unassigned';

            if ($template->vendor_category_id == 2 && $template->role_detail == 'MUA Bride') {
                $vendorId = 1;
                $vendorContactId = 1;
                $status = 'verified';
            }

            if ($template->vendor_category_id == 23 && $template->role_detail == 'Venue / Ballroom') {
                $vendorId = 2;
                $vendorContactId = 2;
                $status = 'verified';
            }

            $slots[] = [
                'event_id' => $eventId,
                'vendor_category_id' => $template->vendor_category_id,
                'vendor_id' => $vendorId,
                'vendor_contact_id' => $vendorContactId,
                'vendor_package_id' => null,
                'session' => $template->session,
                'role_detail' => $template->role_detail,
                'is_included' => $template->is_included,
                'status' => $status,
                'deal_price' => 0,
                'meal_crew' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('event_vendor')->insert($slots);
    }
}
