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
            VendorSeeder::class,
            VendorContactSeeder::class,
            VendorPackageSeeder::class,
            WeddingPackageSeeder::class,
            PackageTemplateSeeder::class,
            PackageVendorPivotSeeder::class,
            EventSeeder::class,
            EoPortfolioSeeder::class,
        ]);
    }
}
