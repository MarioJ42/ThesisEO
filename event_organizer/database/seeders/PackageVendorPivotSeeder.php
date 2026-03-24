<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageVendorPivotSeeder extends Seeder
{
    public function run(): void
    {
        $packageVendors = [
            ['package_id' => 3, 'vendor_category_id' => 2, 'vendor_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 23, 'vendor_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 23, 'vendor_id' => 3, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('package_vendor_pivot')->insert($packageVendors);
    }
}
