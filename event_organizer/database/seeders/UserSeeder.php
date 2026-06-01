<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $users = [
            [
                'name' => 'Fenix',
                'email' => 'fenixorganizer@gmail.com',
                'phone' => '+6285855788100',
                'role' => 'owner',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Jessica Christy',
                'email' => 'jessicachristy@gmail.com',
                'phone' => '081298453210',
                'role' => 'klien',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Januar Andika',
                'email' => 'januarand5@gmail.com',
                'phone' => '081347829913',
                'role' => 'klien',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Syea Pascha',
                'email' => 'syeapascha@gmail.com',
                'phone' => '082155637284',
                'role' => 'klien',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Febriani Ruth',
                'email' => 'febrianiruth@gmail.com',
                'phone' => '085789123456',
                'role' => 'klien',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Rika Alicia',
                'email' => 'rikaaliciah@gmail.com',
                'phone' => '087823456190',
                'role' => 'klien',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Victor Antonius',
                'email' => 'victorantonius@gmail.com',
                'phone' => '081190876543',
                'role' => 'klien',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Dennis Bastian',
                'email' => 'dennisbastian@gmail.com',
                'phone' => '089634567821',
                'role' => 'klien',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        $crewNames = [
            'Hanvy',
            'Lucy',
            'Henry',
            'Eugenia',
            'Raphael',
            'Aprillia',
            'Celine',
            'Alvin',
            'Louisa',
            'Kezia',
            'Mario',
            'Edwin',
            'Marchi',
            'Nathasia',
            'Casey',
            'Jennifer',
            'Christina'
        ];

        $prefixes = ['0812', '0813', '0821', '0857', '0878', '0896'];

        foreach ($crewNames as $name) {
            $users[] = [
                'name' => $name,
                'email' => strtolower($name) . '.crew@gmail.com',
                'phone' => $prefixes[array_rand($prefixes)] . rand(10000000, 99999999),
                'role' => 'crew_eo',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('users')->insert($users);
    }
}
