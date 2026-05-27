<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeddingPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'id' => 1,
                'name' => 'Holy Matrimony',
                'base_price' => 30000000,
                'eo_fee' => 5500000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'Half Day Wedding',
                'base_price' => 54500000,
                'eo_fee' => 9000000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'name' => 'Full Day Wedding',
                'base_price' => 69000000,
                'eo_fee' => 12500000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'name' => 'Dreamy Full Day Wedding',
                'base_price' => 133500000,
                'eo_fee' => 13500000,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('wedding_packages')->insert($packages);
    }
}
