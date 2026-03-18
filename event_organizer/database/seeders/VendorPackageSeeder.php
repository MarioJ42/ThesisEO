<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorPackageSeeder extends Seeder
{
    public function run(): void
    {
        $vendorPackages = [
            ['id' => 1, 'vendor_id' => 1, 'name' => 'Bridal All-in Package', 'min_price' => 25000000, 'max_price' => 30000000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'vendor_id' => 2, 'name' => 'Ballroom + 500 Pax Catering', 'min_price' => 150000000, 'max_price' => 200000000, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('vendor_packages')->insert($vendorPackages);
    }
}
