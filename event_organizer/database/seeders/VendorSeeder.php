<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            [
                'id' => 1,
                'name' => 'Grace Wang Bridal',
                'address' => 'Jl. Taman Mansion Lakarsantri I, Jeruk, Kec. Lakarsantri, Surabaya, Jawa Timur 60212',
                'instagram' => 'gracewangbridal',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'id' => 2,
                'name' => 'Pakuwon Imperial Ballroom',
                'address' => 'Villa Bukit Regency Pakuwon Indah, Jl. Lontar, Lidah Wetan, Kec. Lakarsantri, Surabaya, Jawa Timur 60211',
                'instagram' => 'imperial_ballroom',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('vendors')->insert($vendors);
        $categoryVendor = [
            ['vendor_id' => 1, 'category_id' => 2],
            ['vendor_id' => 1, 'category_id' => 3],
            ['vendor_id' => 1, 'category_id' => 10],
            ['vendor_id' => 1, 'category_id' => 11],
            ['vendor_id' => 1, 'category_id' => 12],
            ['vendor_id' => 2, 'category_id' => 23],
            ['vendor_id' => 2, 'category_id' => 21],
        ];

        DB::table('category_vendor')->insert($categoryVendor);
    }
}
