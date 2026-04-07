<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageVendorPivotSeeder extends Seeder
{
    public function run(): void
    {
        $packageVendors = [
            // PACKAGE 1: HOLY MATRIMONY

            // Grace Wang Bridal
            ['package_id' => 1, 'vendor_category_id' => 2, 'vendor_id' => 34, 'created_at' => now(), 'updated_at' => now()], // MUA
            ['package_id' => 1, 'vendor_category_id' => 3, 'vendor_id' => 34, 'created_at' => now(), 'updated_at' => now()], // Gown
            ['package_id' => 1, 'vendor_category_id' => 10, 'vendor_id' => 34, 'created_at' => now(), 'updated_at' => now()], // Headpiece
            ['package_id' => 1, 'vendor_category_id' => 11, 'vendor_id' => 34, 'created_at' => now(), 'updated_at' => now()], // Robe & Veil

            // JD Bridal
            ['package_id' => 1, 'vendor_category_id' => 2, 'vendor_id' => 35, 'created_at' => now(), 'updated_at' => now()], // MUA
            ['package_id' => 1, 'vendor_category_id' => 3, 'vendor_id' => 35, 'created_at' => now(), 'updated_at' => now()], // Gown
            ['package_id' => 1, 'vendor_category_id' => 10, 'vendor_id' => 35, 'created_at' => now(), 'updated_at' => now()], // Headpiece
            ['package_id' => 1, 'vendor_category_id' => 11, 'vendor_id' => 35, 'created_at' => now(), 'updated_at' => now()], // Robe & Veil

            // Vina Wijayanti
            ['package_id' => 1, 'vendor_category_id' => 2, 'vendor_id' => 36, 'created_at' => now(), 'updated_at' => now()], // MUA
            ['package_id' => 1, 'vendor_category_id' => 3, 'vendor_id' => 36, 'created_at' => now(), 'updated_at' => now()], // Gown
            ['package_id' => 1, 'vendor_category_id' => 10, 'vendor_id' => 36, 'created_at' => now(), 'updated_at' => now()], // Headpiece
            ['package_id' => 1, 'vendor_category_id' => 11, 'vendor_id' => 36, 'created_at' => now(), 'updated_at' => now()], // Robe & Veil

            // Sanjin Suits
            ['package_id' => 1, 'vendor_category_id' => 4, 'vendor_id' => 40, 'created_at' => now(), 'updated_at' => now()], // Suit
            ['package_id' => 1, 'vendor_category_id' => 12, 'vendor_id' => 40, 'created_at' => now(), 'updated_at' => now()], // Tie

            // Daily Photoworks
            ['package_id' => 1, 'vendor_category_id' => 5, 'vendor_id' => 26, 'created_at' => now(), 'updated_at' => now()], // Videographer
            ['package_id' => 1, 'vendor_category_id' => 6, 'vendor_id' => 26, 'created_at' => now(), 'updated_at' => now()], // Photographer

            // Muel Photography
            ['package_id' => 1, 'vendor_category_id' => 5, 'vendor_id' => 27, 'created_at' => now(), 'updated_at' => now()], // Videographer
            ['package_id' => 1, 'vendor_category_id' => 6, 'vendor_id' => 27, 'created_at' => now(), 'updated_at' => now()], // Photographer

            // Diverso Fotografia
            ['package_id' => 1, 'vendor_category_id' => 5, 'vendor_id' => 28, 'created_at' => now(), 'updated_at' => now()], // Videographer
            ['package_id' => 1, 'vendor_category_id' => 6, 'vendor_id' => 28, 'created_at' => now(), 'updated_at' => now()], // Photographer


            // PACKAGE 2: HALF DAY

            // Grace Wang Bridal
            ['package_id' => 2, 'vendor_category_id' => 2, 'vendor_id' => 34, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'vendor_category_id' => 3, 'vendor_id' => 34, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'vendor_category_id' => 10, 'vendor_id' => 34, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'vendor_category_id' => 11, 'vendor_id' => 34, 'created_at' => now(), 'updated_at' => now()],

            // JD Bridal
            ['package_id' => 2, 'vendor_category_id' => 2, 'vendor_id' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'vendor_category_id' => 3, 'vendor_id' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'vendor_category_id' => 10, 'vendor_id' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'vendor_category_id' => 11, 'vendor_id' => 35, 'created_at' => now(), 'updated_at' => now()],

            // Vina Wijayanti
            ['package_id' => 2, 'vendor_category_id' => 2, 'vendor_id' => 36, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'vendor_category_id' => 3, 'vendor_id' => 36, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'vendor_category_id' => 10, 'vendor_id' => 36, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'vendor_category_id' => 11, 'vendor_id' => 36, 'created_at' => now(), 'updated_at' => now()],

            // Reine Atelier
            ['package_id' => 2, 'vendor_category_id' => 2, 'vendor_id' => 39, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'vendor_category_id' => 3, 'vendor_id' => 39, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'vendor_category_id' => 10, 'vendor_id' => 39, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'vendor_category_id' => 11, 'vendor_id' => 39, 'created_at' => now(), 'updated_at' => now()],

            // Bie Hin Tailor
            ['package_id' => 2, 'vendor_category_id' => 4, 'vendor_id' => 41, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'vendor_category_id' => 12, 'vendor_id' => 41, 'created_at' => now(), 'updated_at' => now()],

            // Possa Wedding
            ['package_id' => 2, 'vendor_category_id' => 5, 'vendor_id' => 18, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'vendor_category_id' => 6, 'vendor_id' => 18, 'created_at' => now(), 'updated_at' => now()],

            // Caleos
            ['package_id' => 2, 'vendor_category_id' => 5, 'vendor_id' => 21, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'vendor_category_id' => 6, 'vendor_id' => 21, 'created_at' => now(), 'updated_at' => now()],

            // PJ
            ['package_id' => 2, 'vendor_category_id' => 5, 'vendor_id' => 25, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'vendor_category_id' => 6, 'vendor_id' => 25, 'created_at' => now(), 'updated_at' => now()],

            // MCs
            // David Funata
            ['package_id' => 2, 'vendor_category_id' => 28, 'vendor_id' => 47, 'created_at' => now(), 'updated_at' => now()],

            // Michael Christian
            ['package_id' => 2, 'vendor_category_id' => 28, 'vendor_id' => 42, 'created_at' => now(), 'updated_at' => now()],

            // Juan Filbert
            ['package_id' => 2, 'vendor_category_id' => 28, 'vendor_id' => 43, 'created_at' => now(), 'updated_at' => now()],

            // Elora Decoration
            ['package_id' => 2, 'vendor_category_id' => 30, 'vendor_id' => 33, 'created_at' => now(), 'updated_at' => now()],

            // Groovy Cake
            ['package_id' => 2, 'vendor_category_id' => 32, 'vendor_id' => 52, 'created_at' => now(), 'updated_at' => now()],

            // Its Cake
            ['package_id' => 2, 'vendor_category_id' => 32, 'vendor_id' => 53, 'created_at' => now(), 'updated_at' => now()],

            // Freshtunes
            ['package_id' => 2, 'vendor_category_id' => 33, 'vendor_id' => 49, 'created_at' => now(), 'updated_at' => now()],

            // Glimpse Music
            ['package_id' => 2, 'vendor_category_id' => 33, 'vendor_id' => 50, 'created_at' => now(), 'updated_at' => now()],


            // PACKAGE 3: FULL DAY

            // Grace Wang Bridal
            ['package_id' => 3, 'vendor_category_id' => 2, 'vendor_id' => 34, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 3, 'vendor_id' => 34, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 10, 'vendor_id' => 34, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 11, 'vendor_id' => 34, 'created_at' => now(), 'updated_at' => now()],

            // JD Bridal
            ['package_id' => 3, 'vendor_category_id' => 2, 'vendor_id' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 3, 'vendor_id' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 10, 'vendor_id' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 11, 'vendor_id' => 35, 'created_at' => now(), 'updated_at' => now()],

            // Vina Wijayanti
            ['package_id' => 3, 'vendor_category_id' => 2, 'vendor_id' => 36, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 3, 'vendor_id' => 36, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 10, 'vendor_id' => 36, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 11, 'vendor_id' => 36, 'created_at' => now(), 'updated_at' => now()],

            // Bie Hin Tailor
            ['package_id' => 3, 'vendor_category_id' => 4, 'vendor_id' => 41, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 12, 'vendor_id' => 41, 'created_at' => now(), 'updated_at' => now()],

            // Possa Wedding
            ['package_id' => 3, 'vendor_category_id' => 5, 'vendor_id' => 18, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 6, 'vendor_id' => 18, 'created_at' => now(), 'updated_at' => now()],

            // Revire
            ['package_id' => 3, 'vendor_category_id' => 5, 'vendor_id' => 22, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 6, 'vendor_id' => 22, 'created_at' => now(), 'updated_at' => now()],

            // Sinyo Photography
            ['package_id' => 3, 'vendor_category_id' => 5, 'vendor_id' => 24, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 6, 'vendor_id' => 24, 'created_at' => now(), 'updated_at' => now()],

            // Caleos
            ['package_id' => 3, 'vendor_category_id' => 5, 'vendor_id' => 21, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 6, 'vendor_id' => 21, 'created_at' => now(), 'updated_at' => now()],

            // PJ
            ['package_id' => 3, 'vendor_category_id' => 5, 'vendor_id' => 25, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'vendor_category_id' => 6, 'vendor_id' => 25, 'created_at' => now(), 'updated_at' => now()],

            // MCs
            // David Funata
            ['package_id' => 3, 'vendor_category_id' => 28, 'vendor_id' => 47, 'created_at' => now(), 'updated_at' => now()],

            // Michael Christian
            ['package_id' => 3, 'vendor_category_id' => 28, 'vendor_id' => 42, 'created_at' => now(), 'updated_at' => now()],

            // Juan Filbert
            ['package_id' => 3, 'vendor_category_id' => 28, 'vendor_id' => 43, 'created_at' => now(), 'updated_at' => now()],

            // Elora Decoration
            ['package_id' => 3, 'vendor_category_id' => 30, 'vendor_id' => 33, 'created_at' => now(), 'updated_at' => now()],

            // Groovy Cake
            ['package_id' => 3, 'vendor_category_id' => 32, 'vendor_id' => 52, 'created_at' => now(), 'updated_at' => now()],

            // Its Cake
            ['package_id' => 3, 'vendor_category_id' => 32, 'vendor_id' => 53, 'created_at' => now(), 'updated_at' => now()],

            // Freshtunes
            ['package_id' => 3, 'vendor_category_id' => 33, 'vendor_id' => 49, 'created_at' => now(), 'updated_at' => now()],

            // Glimpse Music
            ['package_id' => 3, 'vendor_category_id' => 33, 'vendor_id' => 50, 'created_at' => now(), 'updated_at' => now()],


            // PACKAGE 4: DREAMY FULL DAY (package_id = 4)

            // House of Lea
            ['package_id' => 4, 'vendor_category_id' => 2, 'vendor_id' => 37, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 4, 'vendor_category_id' => 3, 'vendor_id' => 37, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 4, 'vendor_category_id' => 10, 'vendor_id' => 37, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 4, 'vendor_category_id' => 11, 'vendor_id' => 37, 'created_at' => now(), 'updated_at' => now()],

            // Ovan Putri
            ['package_id' => 4, 'vendor_category_id' => 2, 'vendor_id' => 38, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 4, 'vendor_category_id' => 3, 'vendor_id' => 38, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 4, 'vendor_category_id' => 10, 'vendor_id' => 38, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 4, 'vendor_category_id' => 11, 'vendor_id' => 38, 'created_at' => now(), 'updated_at' => now()],

            // Reine Atelier
            ['package_id' => 4, 'vendor_category_id' => 2, 'vendor_id' => 39, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 4, 'vendor_category_id' => 3, 'vendor_id' => 39, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 4, 'vendor_category_id' => 10, 'vendor_id' => 39, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 4, 'vendor_category_id' => 11, 'vendor_id' => 39, 'created_at' => now(), 'updated_at' => now()],

            // Bie Hin Tailor
            ['package_id' => 4, 'vendor_category_id' => 4, 'vendor_id' => 41, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 4, 'vendor_category_id' => 12, 'vendor_id' => 41, 'created_at' => now(), 'updated_at' => now()],

            // Lovi Story
            ['package_id' => 4, 'vendor_category_id' => 5, 'vendor_id' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 4, 'vendor_category_id' => 6, 'vendor_id' => 20, 'created_at' => now(), 'updated_at' => now()],

            // Kama
            ['package_id' => 4, 'vendor_category_id' => 5, 'vendor_id' => 19, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 4, 'vendor_category_id' => 6, 'vendor_id' => 19, 'created_at' => now(), 'updated_at' => now()],

            // Possa Wedding
            ['package_id' => 4, 'vendor_category_id' => 5, 'vendor_id' => 18, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 4, 'vendor_category_id' => 6, 'vendor_id' => 18, 'created_at' => now(), 'updated_at' => now()],

            // Caleos
            ['package_id' => 4, 'vendor_category_id' => 5, 'vendor_id' => 21, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 4, 'vendor_category_id' => 6, 'vendor_id' => 21, 'created_at' => now(), 'updated_at' => now()],

            // MCs
            // David Funata
            ['package_id' => 4, 'vendor_category_id' => 28, 'vendor_id' => 47, 'created_at' => now(), 'updated_at' => now()],

            // Michael Christian
            ['package_id' => 4, 'vendor_category_id' => 28, 'vendor_id' => 42, 'created_at' => now(), 'updated_at' => now()],

            // Juan Filbert
            ['package_id' => 4, 'vendor_category_id' => 28, 'vendor_id' => 43, 'created_at' => now(), 'updated_at' => now()],

            // Elora Decoration
            ['package_id' => 4, 'vendor_category_id' => 30, 'vendor_id' => 33, 'created_at' => now(), 'updated_at' => now()],

            // Groovy Cake
            ['package_id' => 4, 'vendor_category_id' => 32, 'vendor_id' => 52, 'created_at' => now(), 'updated_at' => now()],

            // Its Cake
            ['package_id' => 4, 'vendor_category_id' => 32, 'vendor_id' => 53, 'created_at' => now(), 'updated_at' => now()],

            // Freshtunes
            ['package_id' => 4, 'vendor_category_id' => 33, 'vendor_id' => 49, 'created_at' => now(), 'updated_at' => now()],

            // Glimpse Music
            ['package_id' => 4, 'vendor_category_id' => 33, 'vendor_id' => 50, 'created_at' => now(), 'updated_at' => now()],

            // Clarity Production
            ['package_id' => 4, 'vendor_category_id' => 42, 'vendor_id' => 54, 'created_at' => now(), 'updated_at' => now()],

            // Athalia Usherettes
            ['package_id' => 4, 'vendor_category_id' => 37, 'vendor_id' => 56, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('package_vendor_pivot')->insert($packageVendors);
    }
}
