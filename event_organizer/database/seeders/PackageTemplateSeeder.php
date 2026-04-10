<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [];
        $morningCategories = [
            1,  // Hotel
            2,  // MUA
            3,  // Gown
            4,  // Suit
            9,  // Hair Styling
            5,  // Videographer
            6,  // Photographer
            11, // Robe & Veil
            12, // Tie
            20, // Meal Crew
            21, // Guest Lunch
            10, // Headpiece
            14, // Hand Bouquet
            15, // Corsage
            7,  // Keepsake
            16, // Ring Box
            18, // Wedding Car
            31, // Church Decoration
            43, // Baloon & Dove
        ];

        $receptionCategories = [
            22, // Venue
            23, // Sound
            24, // Lighting
            28, // MC
            30, // Venue Decoration
            32, // Wedding Cake
            33, // Band
            38, // Effect
            39, // Invitation
            41, // Souvenir
            42, // Photobooth
            5,  // Videographer
            6,  // Photographer
            20, // Meal Crew
        ];

        $includedMorning = [2, 3, 4, 5, 6, 10, 11, 12];
        $includedMorningPkg34 = [2, 3, 4, 5, 6, 10, 11, 12, 43];
        $includedEveningBase = [5, 6, 28, 30, 32, 33];
        $includedEveningPkg4 = [5, 6, 28, 30, 32, 33, 42];

        // PACKAGE 1: Holy Matrimony (Hanya Morning Procession)
        foreach ($morningCategories as $categoryId) {
            $templates[] = [
                'package_id' => 1,
                'vendor_category_id' => $categoryId,
                'session' => 'morning',
                'role_detail' => '-',
                'is_included' => in_array($categoryId, $includedMorning),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // PACKAGE 2: Half Day Wedding (Morning Procession + Reception)
        foreach ($morningCategories as $categoryId) {
            $templates[] = [
                'package_id' => 2,
                'vendor_category_id' => $categoryId,
                'session' => 'morning',
                'role_detail' => '-',
                'is_included' => in_array($categoryId, $includedMorning),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        foreach ($receptionCategories as $categoryId) {
            $templates[] = [
                'package_id' => 2,
                'vendor_category_id' => $categoryId,
                'session' => 'evening',
                'role_detail' => '-',
                'is_included' => in_array($categoryId, $includedEveningBase),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // PACKAGE 3: Full Day Wedding (Morning Procession + Reception)
        foreach ($morningCategories as $categoryId) {
            $templates[] = [
                'package_id' => 3,
                'vendor_category_id' => $categoryId,
                'session' => 'morning',
                'role_detail' => '-',
                'is_included' => in_array($categoryId, $includedMorningPkg34),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        foreach ($receptionCategories as $categoryId) {
            $templates[] = [
                'package_id' => 3,
                'vendor_category_id' => $categoryId,
                'session' => 'evening',
                'role_detail' => '-',
                'is_included' => in_array($categoryId, $includedEveningBase),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // PACKAGE 4: Dreamy Full Day Wedding (Morning + Reception + Usherettes)
        foreach ($morningCategories as $categoryId) {
            $templates[] = [
                'package_id' => 4,
                'vendor_category_id' => $categoryId,
                'session' => 'morning',
                'role_detail' => '-',
                'is_included' => in_array($categoryId, $includedMorningPkg34),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        foreach ($receptionCategories as $categoryId) {
            $templates[] = [
                'package_id' => 4,
                'vendor_category_id' => $categoryId,
                'session' => 'evening',
                'role_detail' => '-',
                'is_included' => in_array($categoryId, $includedEveningPkg4),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        $templates[] = [
            'package_id' => 4,
            'vendor_category_id' => 37,
            'session' => 'evening',
            'role_detail' => '-',
            'is_included' => true,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('package_templates')->insert($templates);
    }
}
