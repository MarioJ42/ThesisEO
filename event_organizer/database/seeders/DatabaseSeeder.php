<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            VendorCategorySeeder::class,
            WeddingPackageSeeder::class,
            VendorSeeder::class,
            VendorContactSeeder::class,
            VendorPackageSeeder::class,
            EventSeeder::class,
        ]);
    }
}
