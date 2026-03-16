<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorContactSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = [
            ['id' => 1, 'vendor_id' => 1, 'name' => 'Sisca (Admin Grace Wang)', 'phone' => '081234567890', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'vendor_id' => 2, 'name' => 'Budi (Marketing Pakuwon)', 'phone' => '089876543210', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('vendor_contacts')->insert($contacts);
    }
}
