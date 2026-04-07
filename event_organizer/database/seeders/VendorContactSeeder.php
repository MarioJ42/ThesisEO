<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorContactSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = [
            //Bie Hin Tailor
            ['id' => 1, 'vendor_id' => 41, 'name' => 'Admin', 'phone' => '+6289668065240', 'created_at' => now(), 'updated_at' => now()],

            //House of Lea
            ['id' => 2, 'vendor_id' => 37, 'name' => 'Lina', 'phone' => '+6281233548993', 'created_at' => now(), 'updated_at' => now()],

            //Novotel Samator East Surabaya
            ['id' => 3, 'vendor_id' => 58, 'name' => 'Riska', 'phone' => '+6281907978313', 'created_at' => now(), 'updated_at' => now()],

            //Butterfly Decoration
            ['id' => 4, 'vendor_id' => 31, 'name' => 'Intan', 'phone' => '+6282257592928', 'created_at' => now(), 'updated_at' => now()],

            //Possa Wedding
            ['id' => 5, 'vendor_id' => 18, 'name' => 'Vera', 'phone' => '+6281319090868', 'created_at' => now(), 'updated_at' => now()],

            //XO Palace Ballroom
            ['id' => 6, 'vendor_id' => 14, 'name' => 'Shierly', 'phone' => '+62 8179329180', 'created_at' => now(), 'updated_at' => now()],

            //Evergreen Cake
            ['id' => 7, 'vendor_id' => 61, 'name' => 'Jois', 'phone' => '+6281938231378', 'created_at' => now(), 'updated_at' => now()],

            //Glow Effect
            ['id' => 8, 'vendor_id' => 62, 'name' => 'Eric', 'phone' => '+628123237445', 'created_at' => now(), 'updated_at' => now()],

            //Manifest
            ['id' => 9, 'vendor_id' => 63, 'name' => 'Fandy', 'phone' => '+6281703090482', 'created_at' => now(), 'updated_at' => now()],

            //Ann Pagar Ayu
            ['id' => 10, 'vendor_id' => 64, 'name' => 'Michelle', 'phone' => '+6281818148209', 'created_at' => now(), 'updated_at' => now()],

            //Clarity Production
            ['id' => 11, 'vendor_id' => 54, 'name' => 'Sisca', 'phone' => '+6287819557195', 'created_at' => now(), 'updated_at' => now()],

            //Stefany Hyperstage Visual
            ['id' => 12, 'vendor_id' => 69, 'name' => 'Stefany', 'phone' => '+6287887374306', 'created_at' => now(), 'updated_at' => now()],

            //Depot Bu Tin
            ['id' => 13, 'vendor_id' => 17, 'name' => 'Admin', 'phone' => '+6282213337790', 'created_at' => now(), 'updated_at' => now()],

            //Nasi Campur Tambak Bayan
            ['id' => 14, 'vendor_id' => 70, 'name' => 'Admin', 'phone' => '+6281331713577', 'created_at' => now(), 'updated_at' => now()],

            //David Funata
            ['id' => 15, 'vendor_id' => 47, 'name' => 'David', 'phone' => '+628175188144', 'created_at' => now(), 'updated_at' => now()],

            //Fenix EO
            ['id' => 16, 'vendor_id' => 1, 'name' => 'Michael', 'phone' => '+6285855788100', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('vendor_contacts')->insert($contacts);
    }
}
