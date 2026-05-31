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

        $categoryVendors = DB::table('category_vendor')->get();

        $basePrices = [
            1 => ['basic' => 8000000, 'standard' => 13000000],  // Hotel
            2 => ['basic' => 3000000, 'standard' => 6000000],   // MUA
            3 => ['basic' => 8000000, 'standard' => 15000000],  // Gown
            4 => ['basic' => 900000, 'standard' => 2500000],    // Suit
            5 => ['basic' => 3500000, 'standard' => 5500000],   // Videographer
            6 => ['basic' => 3500000, 'standard' => 5500000],   // Photographer
            7 => ['basic' => 500000, 'standard' => 1000000],    // Keepsake
            9 => ['basic' => 1000000, 'standard' => 2000000],   // Hair Styling
            10 => ['basic' => 500000, 'standard' => 1500000],   // Headpiece
            11 => ['basic' => 500000, 'standard' => 1500000],   // Robe & Veil
            12 => ['basic' => 150000, 'standard' => 300000],    // Tie
            13 => ['basic' => 1500000, 'standard' => 3000000],  // Room Decoration
            14 => ['basic' => 850000, 'standard' => 1500000],   // Hand Bouquet
            15 => ['basic' => 150000, 'standard' => 300000],    // Corsage
            16 => ['basic' => 300000, 'standard' => 700000],    // Ring Box
            18 => ['basic' => 1500000, 'standard' => 2500000],  // Wedding Car
            19 => ['basic' => 2000000, 'standard' => 4000000],  // Church
            20 => ['basic' => 750000, 'standard' => 1500000],   // Meal Crew (Lump sum)
            21 => ['basic' => 2500000, 'standard' => 5000000],  // Guest Lunch (Lump sum)
            22 => ['basic' => 35000000, 'standard' => 75000000], // Venue
            23 => ['basic' => 2500000, 'standard' => 5000000],  // Sound
            24 => ['basic' => 1500000, 'standard' => 3500000],  // Lighting
            25 => ['basic' => 1500000, 'standard' => 3000000],  // Genset
            26 => ['basic' => 4000000, 'standard' => 8000000],  // LED
            27 => ['basic' => 1000000, 'standard' => 2000000],  // LCD
            28 => ['basic' => 1500000, 'standard' => 2500000],  // MC
            29 => ['basic' => 1000000, 'standard' => 2000000],  // Animation
            30 => ['basic' => 6000000, 'standard' => 12500000], // Venue Decoration
            31 => ['basic' => 3500000, 'standard' => 6000000],  // Church Decoration
            32 => ['basic' => 1500000, 'standard' => 3000000],  // Wedding Cake
            33 => ['basic' => 2500000, 'standard' => 4500000],  // Band
            37 => ['basic' => 1000000, 'standard' => 1500000],  // Usherettes
            38 => ['basic' => 1000000, 'standard' => 2000000],  // Effect
            39 => ['basic' => 1000000, 'standard' => 2000000],  // Invitation
            40 => ['basic' => 1000000, 'standard' => 2000000],  // Digital Guest Book
            41 => ['basic' => 2000000, 'standard' => 5000000],  // Souvenir
            42 => ['basic' => 2000000, 'standard' => 3500000],  // Photobooth
            43 => ['basic' => 500000, 'standard' => 1000000],   // Baloon & Dove
            44 => ['basic' => 300000, 'standard' => 600000],    // Flower Shower
            45 => ['basic' => 500000, 'standard' => 1000000],   // Misua & Angco Tea
            46 => ['basic' => 1000000, 'standard' => 2000000],  // Pyramid Fountain & Toast
        ];

        $vendorPackages = [];

        foreach ($categoryVendors as $cv) {
            $catId = $cv->category_id;
            $vendorId = $cv->vendor_id;

            $basicBase = $basePrices[$catId]['basic'] ?? 1000000;
            $stdBase = $basePrices[$catId]['standard'] ?? 2500000;

            $basicNet = round(($basicBase * (1 + rand(-5, 10) / 100)) / 50000) * 50000;
            $stdNet = round(($stdBase * (1 + rand(-5, 10) / 100)) / 50000) * 50000;

            $basicSell = round($basicNet * 1.15);
            $stdSell = round($stdNet * 1.15);

            $vendorPackages[] = [
                'vendor_id' => $vendorId,
                'vendor_category_id' => $catId,
                'name' => 'Basic Service',
                'price' => $basicSell,
                'net_price' => $basicNet,
                'details' => 'Standard equipment and minimum personnel coverage for your event.',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $vendorPackages[] = [
                'vendor_id' => $vendorId,
                'vendor_category_id' => $catId,
                'name' => 'Standard Service',
                'price' => $stdSell,
                'net_price' => $stdNet,
                'details' => 'Premium equipment, additional hours, and extra personnel coverage.',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach (array_chunk($vendorPackages, 200) as $chunk) {
            DB::table('vendor_packages')->insert($chunk);
        }
    }
}
