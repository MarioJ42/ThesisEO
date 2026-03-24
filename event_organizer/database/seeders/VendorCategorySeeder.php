<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Hotel', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MUA', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gown', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Suit', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Videographer', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Photographer', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Keepsake', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'WCC', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hair Styling', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Headpiece', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Robe & Veil', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tie', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Room Decoration', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hand Bouquet', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Corsage', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ring Box', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Conceptor', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Wedding Car', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Church', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Meal Crew', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Guest Lunch', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Venue', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sound', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lighting', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Genset', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LED', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LCD', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MC', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Animation', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Venue Decoration', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Church Decoration', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Wedding Cake', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Band', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Orchestra', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Guest Star', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Entertainment', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Usherettes', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Effect', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Invitation', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Digital Guest Book', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Souvenir', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Photobooth', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('vendor_categories')->insert($categories);
    }
}
