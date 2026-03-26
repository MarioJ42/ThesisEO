<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EoPortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $portfolios = [
            [
                'title' => 'Wedding of Wandy & Vita',
                'description' => '-',
                'image_path' => 'portfolios/1.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Wedding of Gerry & Lily',
                'description' => '-',
                'image_path' => 'portfolios/2.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Wedding of Samuel & Ellis',
                'description' => '-',
                'image_path' => 'portfolios/3.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Wedding of Syea & Tasya',
                'description' => '-',
                'image_path' => 'portfolios/4.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Wedding of Steven & Risca',
                'description' => '-',
                'image_path' => 'portfolios/5.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Wedding of Aditya & Michelle',
                'description' => '-',
                'image_path' => 'portfolios/6.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('eo_portfolios')->insert($portfolios);
    }
}
