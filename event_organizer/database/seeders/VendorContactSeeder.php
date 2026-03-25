<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorContactSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = [
            ['id' => 1, 'vendor_id' => 1, 'name' => 'Evelyn (Admin)', 'phone' => '081234567890', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'vendor_id' => 2, 'name' => 'Celyn (Marketing)', 'phone' => '089876543210', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'vendor_id' => 3, 'name' => 'Jessica (Marketing)', 'phone' => '089876543222', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'vendor_id' => 3, 'name' => 'Aiko (Marketing)', 'phone' => '089876543333', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('vendor_contacts')->insert($contacts);
    }
}
