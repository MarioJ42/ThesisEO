<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorPackageSeeder extends Seeder
{
    public function run(): void
    {
        $vendorPackages = [
            ['id' => 1, 'vendor_id' => 18, 'vendor_category_id' => 6, 'name' => 'Standard Photographer Wedding Day', 'price' => 15000000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'vendor_id' => 18, 'vendor_category_id' => 6, 'name' => 'Premium Photographer Wedding Day', 'price' => 18000000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'vendor_id' => 18, 'vendor_category_id' => 5, 'name' => 'Standard Videographer Wedding Day', 'price' => 17000000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'vendor_id' => 18, 'vendor_category_id' => 5, 'name' => 'Premium Videographer Wedding Day', 'price' => 20000000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'vendor_id' => 19, 'vendor_category_id' => 6, 'name' => 'Standard Photographer Wedding Day', 'price' => 19000000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'vendor_id' => 19, 'vendor_category_id' => 5, 'name' => 'Standard Videographer Wedding Day', 'price' => 21000000, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('vendor_packages')->insert($vendorPackages);
    }
}
