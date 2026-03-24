<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            //Morning Procession
            ['package_id' => 3, 'vendor_category_id' => 1, 'session' => 'morning', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 6, 'session' => 'morning', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 5, 'session' => 'morning', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 14, 'session' => 'morning', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 15, 'session' => 'morning', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 7, 'session' => 'morning', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 16, 'session' => 'morning', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 19, 'session' => 'morning', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 31, 'session' => 'morning', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 2, 'session' => 'morning', 'role_detail' => "Bride", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 9, 'session' => 'morning', 'role_detail' => "Bride", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 11, 'session' => 'morning', 'role_detail' => "Bride", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 3, 'session' => 'morning', 'role_detail' => "Bride", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 10, 'session' => 'morning', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 2, 'session' => 'morning', 'role_detail' => "Groom", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 9, 'session' => 'morning', 'role_detail' => "Groom", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 12, 'session' => 'morning', 'role_detail' => "Groom", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 18, 'session' => 'morning', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 21, 'session' => 'morning', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 20, 'session' => 'morning', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],

            // Reception
            ['package_id' => 3, 'vendor_category_id' => 22, 'session' => 'evening', 'role_detail' => '-', 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 30, 'session' => 'evening', 'role_detail' => '-', 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 6, 'session' => 'evening', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 5, 'session' => 'evening', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 23, 'session' => 'evening', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 24, 'session' => 'evening', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 38, 'session' => 'evening', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 32, 'session' => 'evening', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 28, 'session' => 'evening', 'role_detail' => "-", 'is_included' => true, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 33, 'session' => 'evening', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 41, 'session' => 'evening', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 39, 'session' => 'evening', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 42, 'session' => 'evening', 'role_detail' => "-", 'is_included' => true, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 20, 'session' => 'evening', 'role_detail' => "-", 'is_included' => false, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('package_templates')->insert($templates);
    }
}
