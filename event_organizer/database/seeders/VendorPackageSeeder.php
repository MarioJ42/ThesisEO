<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorPackageSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('vendor_packages')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $vendorPackages = [
            // 1. FOTOGRAFI (Cat 6) & VIDEOGRAFI (Cat 5)
            // Note: Harga paket bundling di-split 50:50
            // Diverso Photography (28)
            ['vendor_id' => 28, 'vendor_category_id' => 6, 'name' => 'Standard Service', 'price' => 3500000],
            ['vendor_id' => 28, 'vendor_category_id' => 5, 'name' => 'Standard Service', 'price' => 3500000],

            // Muel Photography (27)
            ['vendor_id' => 27, 'vendor_category_id' => 6, 'name' => 'Basic Service', 'price' => 3500000],
            ['vendor_id' => 27, 'vendor_category_id' => 5, 'name' => 'Basic Service', 'price' => 3500000],
            ['vendor_id' => 27, 'vendor_category_id' => 6, 'name' => 'Standard Service', 'price' => 5500000],
            ['vendor_id' => 27, 'vendor_category_id' => 5, 'name' => 'Standard Service', 'price' => 5500000],

            // Daily Photoworks (26)
            ['vendor_id' => 26, 'vendor_category_id' => 6, 'name' => 'Basic Service', 'price' => 3500000],
            ['vendor_id' => 26, 'vendor_category_id' => 5, 'name' => 'Basic Service', 'price' => 3500000],
            ['vendor_id' => 26, 'vendor_category_id' => 6, 'name' => 'Standard Service', 'price' => 5500000],
            ['vendor_id' => 26, 'vendor_category_id' => 5, 'name' => 'Standard Service', 'price' => 5500000],

            // Possa Photography (18)
            ['vendor_id' => 18, 'vendor_category_id' => 6, 'name' => 'Basic Service', 'price' => 5500000],
            ['vendor_id' => 18, 'vendor_category_id' => 5, 'name' => 'Basic Service', 'price' => 5500000],
            ['vendor_id' => 18, 'vendor_category_id' => 6, 'name' => 'Standard Service', 'price' => 8750000],
            ['vendor_id' => 18, 'vendor_category_id' => 5, 'name' => 'Standard Service', 'price' => 8750000],

            // Retro Magis (72)
            ['vendor_id' => 72, 'vendor_category_id' => 6, 'name' => 'Standard Service', 'price' => 8750000],
            ['vendor_id' => 72, 'vendor_category_id' => 5, 'name' => 'Standard Service', 'price' => 8750000],

            // Dave Galery (23)
            ['vendor_id' => 23, 'vendor_category_id' => 6, 'name' => 'Basic Service', 'price' => 5500000],
            ['vendor_id' => 23, 'vendor_catzegory_id' => 5, 'name' => 'Basic Service', 'price' => 5500000],
            ['vendor_id' => 23, 'vendor_category_id' => 6, 'name' => 'Standard Service', 'price' => 8750000],
            ['vendor_id' => 23, 'vendor_category_id' => 5, 'name' => 'Standard Service', 'price' => 8750000],

            // PJ (25)
            ['vendor_id' => 25, 'vendor_category_id' => 6, 'name' => 'Standard Service', 'price' => 8750000],
            ['vendor_id' => 25, 'vendor_category_id' => 5, 'name' => 'Standard Service', 'price' => 8750000],


            // 2. BRIDAL / GOWN (Cat 3)
            // JD Bridal (35)
            ['vendor_id' => 35, 'vendor_category_id' => 3, 'name' => 'Basic Service', 'price' => 13000000],
            ['vendor_id' => 35, 'vendor_category_id' => 3, 'name' => 'Standard Service', 'price' => 18000000],

            // Grace Wang Bridal (34)
            ['vendor_id' => 34, 'vendor_category_id' => 3, 'name' => 'Basic Service', 'price' => 13000000],
            ['vendor_id' => 34, 'vendor_category_id' => 3, 'name' => 'Standard Service', 'price' => 18000000],
            ['vendor_id' => 34, 'vendor_category_id' => 3, 'name' => 'Premium Service', 'price' => 25000000],

            // House of Lea (37)
            ['vendor_id' => 37, 'vendor_category_id' => 3, 'name' => 'Standard Service', 'price' => 22000000],

            // Ovan Putri (38)
            ['vendor_id' => 38, 'vendor_category_id' => 3, 'name' => 'Standard Service', 'price' => 22000000],

            // 3. SUITS / JAS (Cat 4)
            // Sanjin Suits (40)
            ['vendor_id' => 40, 'vendor_category_id' => 4, 'name' => 'Standard Service', 'price' => 450000],

            // Bie Hin Tailor (41)
            ['vendor_id' => 41, 'vendor_category_id' => 4, 'name' => 'Standard Service', 'price' => 900000],
            ['vendor_id' => 41, 'vendor_category_id' => 4, 'name' => 'Premium Service', 'price' => 3500000],

            // 4. MASTER OF CEREMONY (Cat 28)
            // Michael Christian (42)
            ['vendor_id' => 42, 'vendor_category_id' => 28, 'name' => 'Standard Service', 'price' => 2500000],
            ['vendor_id' => 42, 'vendor_category_id' => 28, 'name' => 'Premium Service', 'price' => 7000000],

            // Juan Filbert (43)
            ['vendor_id' => 43, 'vendor_category_id' => 28, 'name' => 'Standard Service', 'price' => 2500000],
            ['vendor_id' => 43, 'vendor_category_id' => 28, 'name' => 'Premium Service', 'price' => 7000000],

            // Charly Hong (45)
            ['vendor_id' => 45, 'vendor_category_id' => 28, 'name' => 'Standard Service', 'price' => 2500000],
            ['vendor_id' => 45, 'vendor_category_id' => 28, 'name' => 'Premium Service', 'price' => 7000000],

            // David Funata (47)
            ['vendor_id' => 47, 'vendor_category_id' => 28, 'name' => 'Standard Service', 'price' => 2500000],
            ['vendor_id' => 47, 'vendor_category_id' => 28, 'name' => 'Premium Service', 'price' => 7000000],

            // Fransiskus Nduti (44)
            ['vendor_id' => 44, 'vendor_category_id' => 28, 'name' => 'Standard Service', 'price' => 2500000],
            ['vendor_id' => 44, 'vendor_category_id' => 28, 'name' => 'Premium Service', 'price' => 7000000],

            //Piniela (48)
            ['vendor_id' => 48, 'vendor_category_id' => 28, 'name' => 'Standard Service', 'price' => 7000000],

            // 5. DECORATION (Cat 30)
            // Elora Decoration (33)
            ['vendor_id' => 33, 'vendor_category_id' => 30, 'name' => 'Standard Service', 'price' => 6000000],

            // Butterfly Decoration (31)
            ['vendor_id' => 31, 'vendor_category_id' => 30, 'name' => 'Standard Service', 'price' => 6000000],
            ['vendor_id' => 31, 'vendor_category_id' => 30, 'name' => 'Premium Service', 'price' => 45000000],

            // Best Decoration (29)
            ['vendor_id' => 29, 'vendor_category_id' => 30, 'name' => 'Standard Service', 'price' => 45000000],

            // 6. WEDDING CAKE (Cat 32)
            // Groovy Cake (52)
            ['vendor_id' => 52, 'vendor_category_id' => 32, 'name' => 'Standard Service', 'price' => 3000000],

            //BAND / ENTERTAINMENT (Cat 33)
            // Freshtunes (49)
            ['vendor_id' => 49, 'vendor_category_id' => 33, 'name' => 'Standard Service', 'price' => 3500000],
            ['vendor_id' => 49, 'vendor_category_id' => 33, 'name' => 'Premium Service', 'price' => 6000000],

            // 8. PHOTOBOOTH (Cat 42)
            // Clarity Production (54)
            ['vendor_id' => 54, 'vendor_category_id' => 42, 'name' => 'Standard Service', 'price' => 3750000],

            // 9. USHERETTES (Cat 37)
            // Athalia (56)
            ['vendor_id' => 56, 'vendor_category_id' => 37, 'name' => 'Standard Service', 'price' => 1500000],

            // 10. DIGITAL GUEST BOOK / RSVP (Cat 40)
            // Fenix EO (1)
            ['vendor_id' => 1, 'vendor_category_id' => 40, 'name' => 'Standard Service', 'price' => 2000000],

            // 11. Hand Bouquet (Cat 14)
            // Four Clover (71)
            ['vendor_id' => 71, 'vendor_category_id' => 14, 'name' => 'Standard Service', 'price' => 1500000],
        ];

        $formattedPackages = array_map(function ($pkg) {
            $pkg['created_at'] = now();
            $pkg['updated_at'] = now();
            return $pkg;
        }, $vendorPackages);

        foreach (array_chunk($formattedPackages, 50) as $chunk) {
            DB::table('vendor_packages')->insert($chunk);
        }
    }
}
