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
            ['name' => 'Dress', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Suit', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Videographer', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Photographer', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Decoration', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Keepsake', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'WCC', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('vendor_categories')->insert($categories);
    }
}
