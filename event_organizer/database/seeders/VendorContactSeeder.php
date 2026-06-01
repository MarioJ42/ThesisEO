<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorContactSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('vendor_contacts')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $vendors = DB::table('vendors')->get();

        $modernNames = [
            'Alexander',
            'Austin',
            'Brandon',
            'Bryan',
            'Calvin',
            'Christian',
            'Christopher',
            'Daniel',
            'Darren',
            'David',
            'Dennis',
            'Edward',
            'Edwin',
            'Eric',
            'Felix',
            'Gabriel',
            'Glenn',
            'Ivan',
            'Jason',
            'Jeffrey',
            'Jonathan',
            'Joshua',
            'Justin',
            'Kevin',
            'Leon',
            'Marcel',
            'Matthew',
            'Michael',
            'Nathan',
            'Nicholas',
            'Raymond',
            'Richard',
            'Ronald',
            'Samuel',
            'Steven',
            'Thomas',
            'Victor',
            'Vincent',
            'William',
            'Winston',

            'Alicia',
            'Amanda',
            'Angelina',
            'Audrey',
            'Aurelia',
            'Bella',
            'Carissa',
            'Caroline',
            'Celine',
            'Chelsea',
            'Cindy',
            'Clarissa',
            'Cynthia',
            'Erica',
            'Evelyn',
            'Felicia',
            'Fiona',
            'Gabriella',
            'Giselle',
            'Grace',
            'Irene',
            'Jane',
            'Jessica',
            'Jocelyn',
            'Karen',
            'Kelly',
            'Lauren',
            'Liv',
            'Marissa',
            'Melissa',
            'Michelle',
            'Nadine',
            'Natasha',
            'Olivia',
            'Patricia',
            'Priscilla',
            'Rachel',
            'Regina',
            'Sharon',
            'Sherly',
            'Stella',
            'Stephanie',
            'Sylvia',
            'Tania',
            'Valerie',
            'Vania',
            'Vera',
            'Veronica',
            'Victoria',
            'Vivian'
        ];


        $prefixes = ['+62812', '+62813', '+62821', '+62822', '+62818', '+62819', '+62878', '+62895', '+62896', '+62811'];

        $contacts = [];


        $specificContacts = [
            1  => 'Michael',   // Fenix EO
            14 => 'Shierly',   // XO Palace
            18 => 'Vera',      // Possa Wedding
            31 => 'Intan',     // Butterfly Decoration
            37 => 'Lina',      // House of Lea
            41 => 'Richard',   // Bie Hin Tailor (diubah dari "Admin" menjadi lebih modern)
            42 => 'Michael',   // MC Michael Christian
            43 => 'Juan',      // MC Juan Filbert
            47 => 'David',     // MC David Funata
            54 => 'Sisca',     // Clarity Production
            61 => 'Jois',      // Evergreen Cake
            62 => 'Eric',      // Glow Effect
            63 => 'Fandy',     // Manifest Band
            64 => 'Michelle',  // Ann Pagar Ayu
            69 => 'Stefany',   // Stefany Hyperstage
            71 => 'Celine',    // Four Clover
            78 => 'William', // Lancar Sound
        ];

        foreach ($vendors as $vendor) {
            $name = $specificContacts[$vendor->id] ?? $modernNames[array_rand($modernNames)];

            $prefix = $prefixes[array_rand($prefixes)];
            $suffix = rand(1000000, 99999999);
            $phone = $prefix . $suffix;

            $contacts[] = [
                'vendor_id'  => $vendor->id,
                'name'       => $name,
                'phone'      => $phone,
                'is_primary' => true,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];


            if (rand(1, 100) <= 15) {
                $contacts[] = [
                    'vendor_id'  => $vendor->id,
                    'name'       => $modernNames[array_rand($modernNames)],
                    'phone'      => $prefixes[array_rand($prefixes)] . rand(1000000, 99999999),
                    'is_primary' => false,
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($contacts, 100) as $chunk) {
            DB::table('vendor_contacts')->insert($chunk);
        }
    }
}
