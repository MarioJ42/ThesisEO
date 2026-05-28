<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
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
                ],
            ],
            [
                'name' => 'Kezia',
                'email' => 'k@k.com',
                'phone' => '081234567890',
                'role' => 'crew_eo',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Clarin',
                'email' => 'c@c.com',
                'phone' => '081234567890',
                'role' => 'crew_eo',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('users')->insert($users);
    }
}
