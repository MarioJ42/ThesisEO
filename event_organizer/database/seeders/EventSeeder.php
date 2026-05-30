<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // 1. EVENT PERTAMA: YOSHUA & JESSICA
        $eventId = DB::table('events')->insertGetId([
            'client_id' => 2,
            'pl_id' => 1,
            'package_id' => 3,
            'title' => 'Wedding of Yoshua & Jessica',
            'event_date' => '2026-05-28',
            'status' => 'planning',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $docAssignments = [
            ['category' => 'Suit', 'vendor_id' => 41, 'pic_name' => 'Admin', 'pic_phone' => '+6289668065240', 'session' => 'morning'],
            ['category' => 'Gown', 'vendor_id' => 37, 'pic_name' => 'Lina', 'pic_phone' => '+6281233548993', 'session' => 'morning'],
            ['category' => 'MUA', 'vendor_id' => 37, 'pic_name' => 'Lina', 'pic_phone' => '+6281233548993', 'session' => 'morning'],
            ['category' => 'Hotel', 'vendor_id' => 58, 'pic_name' => 'Riska', 'pic_phone' => '+6281907978313', 'session' => 'morning'],
            ['category' => 'Room Decoration', 'vendor_id' => 31, 'pic_name' => 'Intan', 'pic_phone' => '+6282257592928', 'session' => 'morning'],
            ['category' => 'Hand Bouquet', 'vendor_id' => 31, 'pic_name' => 'Intan', 'pic_phone' => '+6282257592928', 'session' => 'morning'],
            ['category' => 'Corsage', 'vendor_id' => 31, 'pic_name' => 'Intan', 'pic_phone' => '+6282257592928', 'session' => 'morning'],
            ['category' => 'Wedding Car', 'vendor_id' => 37, 'pic_name' => 'Lina', 'pic_phone' => '+6281233548993', 'session' => 'morning'],
            ['category' => 'Keepsake', 'vendor_id' => 59, 'pic_name' => 'Admin', 'pic_phone' => '-', 'session' => 'morning'],
            ['category' => 'Photographer', 'vendor_id' => 18, 'pic_name' => 'Vera', 'pic_phone' => '+6281319090868', 'session' => 'morning'],
            ['category' => 'Videographer', 'vendor_id' => 18, 'pic_name' => 'Vera', 'pic_phone' => '+6281319090868', 'session' => 'morning'],
            ['category' => 'Photographer', 'vendor_id' => 18, 'pic_name' => 'Vera', 'pic_phone' => '+6281319090868', 'session' => 'evening'],
            ['category' => 'Videographer', 'vendor_id' => 18, 'pic_name' => 'Vera', 'pic_phone' => '+6281319090868', 'session' => 'evening'],
            ['category' => 'Church', 'vendor_id' => 60, 'pic_name' => 'Admin', 'pic_phone' => '-', 'session' => 'morning'],
            ['category' => 'Church Decoration', 'vendor_id' => 60, 'pic_name' => 'Admin', 'pic_phone' => '-', 'session' => 'morning'],
            ['category' => 'Baloon & Dove', 'vendor_id' => 1, 'pic_name' => 'Michael', 'pic_phone' => '+6285855788100', 'session' => 'morning'],
            ['category' => 'Venue', 'vendor_id' => 14, 'pic_name' => 'Shierly', 'pic_phone' => '+628179329180', 'session' => 'evening'],
            ['category' => 'Pyramid Fountain & Toast', 'vendor_id' => 14, 'pic_name' => 'Shierly', 'pic_phone' => '+628179329180', 'session' => 'evening'],
            ['category' => 'Venue Decoration', 'vendor_id' => 31, 'pic_name' => 'Intan', 'pic_phone' => '+6282257592928', 'session' => 'evening'],
            ['category' => 'Wedding Cake', 'vendor_id' => 61, 'pic_name' => 'Jois', 'pic_phone' => '+6281938231378', 'session' => 'evening'],
            ['category' => 'Lighting', 'vendor_id' => 62, 'pic_name' => 'Eric', 'pic_phone' => '+628123237445', 'session' => 'evening'],
            ['category' => 'Effect', 'vendor_id' => 62, 'pic_name' => 'Eric', 'pic_phone' => '+628123237445', 'session' => 'evening'],
            ['category' => 'Sound', 'vendor_id' => 14, 'pic_name' => 'Shierly', 'pic_phone' => '+628179329180', 'session' => 'evening'],
            ['category' => 'Genset', 'vendor_id' => 1, 'pic_name' => 'Michael', 'pic_phone' => '+6285855788100', 'session' => 'evening'],
            ['category' => 'LCD', 'vendor_id' => 14, 'pic_name' => 'Shierly', 'pic_phone' => '+628179329180', 'session' => 'evening'],
            ['category' => 'Band', 'vendor_id' => 63, 'pic_name' => 'Fandy', 'pic_phone' => '+6281703090482', 'session' => 'evening'],
            ['category' => 'MC', 'vendor_id' => 47, 'pic_name' => 'David', 'pic_phone' => '+628175188144', 'session' => 'evening'],
            ['category' => 'Usherettes', 'vendor_id' => 64, 'pic_name' => 'Michelle', 'pic_phone' => '+6281818148209', 'session' => 'evening'],
            ['category' => 'Photobooth', 'vendor_id' => 54, 'pic_name' => 'Sisca', 'pic_phone' => '+6287819557195', 'session' => 'evening'],
            ['category' => 'Souvenir', 'vendor_id' => 65, 'pic_name' => 'Admin', 'pic_phone' => '-', 'session' => 'evening'],
            ['category' => 'Invitation', 'vendor_id' => 66, 'pic_name' => 'Admin', 'pic_phone' => '-', 'session' => 'evening'],
            ['category' => 'Animation', 'vendor_id' => 69, 'pic_name' => 'Stefany', 'pic_phone' => '+6287887374306', 'session' => 'evening'],
            ['category' => 'Robe & Veil', 'vendor_id' => 37, 'pic_name' => 'Lina', 'pic_phone' => '+6281233548993', 'session' => 'morning'],
            ['category' => 'Headpiece', 'vendor_id' => 37, 'pic_name' => 'Lina', 'pic_phone' => '+6281233548993', 'session' => 'morning'],
            ['category' => 'Tie', 'vendor_id' => 41, 'pic_name' => 'Admin', 'pic_phone' => '+6289668065240', 'session' => 'morning'],
            ['category' => 'Meal Crew', 'vendor_id' => 17, 'pic_name' => 'Admin', 'pic_phone' => '+6282213337790', 'session' => 'morning'],
            ['category' => 'Ring Box', 'vendor_id' => 57, 'pic_name' => 'Pribadi', 'pic_phone' => '-', 'session' => 'morning'],
            ['category' => 'Meal Crew', 'vendor_id' => 14, 'pic_name' => 'Shierly', 'pic_phone' => '+628179329180', 'session' => 'evening'],
            ['category' => 'LED', 'vendor_id' => 14, 'pic_name' => 'Shierly', 'pic_phone' => '+628179329180', 'session' => 'evening'],
            ['category' => 'Hair Styling', 'vendor_id' => 37, 'pic_name' => 'Lina', 'pic_phone' => '+6281233548993', 'session' => 'morning'],
            ['category' => 'Guest Lunch', 'vendor_id' => 70, 'pic_name' => 'Admin', 'pic_phone' => '+6281331713577', 'session' => 'morning'],
            ['category' => 'Misua & Angco Tea', 'vendor_id' => 1, 'pic_name' => 'Michael', 'pic_phone' => '+6285855788100', 'session' => 'morning'],
        ];

        $categories = DB::table('vendor_categories')->pluck('id', 'name')->toArray();
        $slots = [];

        foreach ($docAssignments as $data) {
            if (isset($categories[$data['category']])) {
                $categoryId = $categories[$data['category']];

                $existingContact = DB::table('vendor_contacts')
                    ->where('vendor_id', $data['vendor_id'])
                    ->where('name', $data['pic_name'])
                    ->first();

                if ($existingContact) {
                    $contactId = $existingContact->id;
                } else {
                    $contactId = DB::table('vendor_contacts')->insertGetId([
                        'vendor_id' => $data['vendor_id'],
                        'name' => $data['pic_name'],
                        'phone' => $data['pic_phone'],
                        'is_primary' => true,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $isIncluded = DB::table('package_vendor_pivot')
                    ->where('package_id', 3)
                    ->where('vendor_category_id', $categoryId)
                    ->where('vendor_id', $data['vendor_id'])
                    ->exists();

                $slots[] = [
                    'event_id' => $eventId,
                    'vendor_category_id' => $categoryId,
                    'vendor_id' => $data['vendor_id'],
                    'vendor_contact_id' => $contactId,
                    'vendor_package_id' => null,
                    'session' => $data['session'],
                    'role_detail' => '-',
                    'is_included' => $isIncluded,
                    'status' => 'verified',
                    'deal_price' => 0,
                    'meal_crew' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $templates = DB::table('package_templates')->where('package_id', 3)->get();
        $assignedCategoryIds = array_column($slots, 'vendor_category_id');

        foreach ($templates as $template) {
            if (!in_array($template->vendor_category_id, $assignedCategoryIds)) {
                $slots[] = [
                    'event_id' => $eventId,
                    'vendor_category_id' => $template->vendor_category_id,
                    'vendor_id' => null,
                    'vendor_contact_id' => null,
                    'vendor_package_id' => null,
                    'session' => $template->session,
                    'role_detail' => '-',
                    'is_included' => $template->is_included,
                    'status' => 'unassigned',
                    'deal_price' => 0,
                    'meal_crew' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('event_vendor')->insert($slots);

        // 2. EVENT KEDUA: JANUAR & RISKA (Half Day Wedding - 150 Pax)
        $eventId2 = DB::table('events')->insertGetId([
            'client_id' => 3,
            'pl_id' => 1,
            'package_id' => 2,
            'title' => 'Wedding of Januar & Riska',
            'event_date' => '2026-10-15',
            'status' => 'planning',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $januarAssignments = [
            ['category' => 'Venue Decoration', 'vendor_id' => 31, 'pic_name' => 'Intan', 'pic_phone' => '+6282257592928', 'session' => 'evening', 'deal_price' => 12500000],
            ['category' => 'Photographer', 'vendor_id' => 18, 'pic_name' => 'Vera', 'pic_phone' => '+6281319090868', 'session' => 'morning', 'deal_price' => 8000000],
            ['category' => 'Videographer', 'vendor_id' => 18, 'pic_name' => 'Vera', 'pic_phone' => '+6281319090868', 'session' => 'morning', 'deal_price' => 8000000],
            ['category' => 'Gown', 'vendor_id' => 34, 'pic_name' => 'Admin', 'pic_phone' => '-', 'session' => 'morning', 'deal_price' => 13000000],
            ['category' => 'Suit', 'vendor_id' => 41, 'pic_name' => 'Admin', 'pic_phone' => '+6289668065240', 'session' => 'morning', 'deal_price' => 6300000],
            ['category' => 'Wedding Cake', 'vendor_id' => 52, 'pic_name' => 'Admin', 'pic_phone' => '-', 'session' => 'evening', 'deal_price' => 2500000],
            ['category' => 'Lighting', 'vendor_id' => 62, 'pic_name' => 'Eric', 'pic_phone' => '+628123237445', 'session' => 'evening', 'deal_price' => 2750000],
            ['category' => 'Hotel', 'vendor_id' => 77, 'pic_name' => 'Admin', 'pic_phone' => '-', 'session' => 'morning', 'deal_price' => 13000000],
            ['category' => 'Venue', 'vendor_id' => 76, 'pic_name' => 'Admin', 'pic_phone' => '-', 'session' => 'evening', 'deal_price' => 83700000],
        ];

        $slotsJanuar = [];

        foreach ($januarAssignments as $data) {
            if (isset($categories[$data['category']])) {
                $categoryId = $categories[$data['category']];

                $existingContact = DB::table('vendor_contacts')
                    ->where('vendor_id', $data['vendor_id'])
                    ->where('name', $data['pic_name'])
                    ->first();

                if ($existingContact) {
                    $contactId = $existingContact->id;
                } else {
                    $contactId = DB::table('vendor_contacts')->insertGetId([
                        'vendor_id' => $data['vendor_id'],
                        'name' => $data['pic_name'],
                        'phone' => $data['pic_phone'],
                        'is_primary' => true,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $slotsJanuar[] = [
                    'event_id' => $eventId2,
                    'vendor_category_id' => $categoryId,
                    'vendor_id' => $data['vendor_id'],
                    'vendor_contact_id' => $contactId,
                    'vendor_package_id' => null,
                    'session' => $data['session'],
                    'role_detail' => '-',
                    'is_included' => false,
                    'status' => 'signed',
                    'deal_price' => $data['deal_price'],
                    'meal_crew' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('event_vendor')->insert($slotsJanuar);
    }
}
