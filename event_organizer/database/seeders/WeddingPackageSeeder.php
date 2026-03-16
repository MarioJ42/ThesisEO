<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeddingPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            ['id' => 1, 'name' => 'Holy Matrimony', 'base_price' => 15000000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Half Day Wedding', 'base_price' => 35000000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Full Day Wedding', 'base_price' => 50000000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Dreamy Full Day Wedding', 'base_price' => 85000000, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('wedding_packages')->insert($packages);
    }
}
