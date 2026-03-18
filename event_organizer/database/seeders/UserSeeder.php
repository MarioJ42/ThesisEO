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
                'email' => 'f@f.com',
                'phone' => '081234567890',
                'role' => 'owner',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mario',
                'email' => 'm@m.com',
                'phone' => '081234567890',
                'role' => 'pl',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Kezia',
                'email' => 'k@k.com',
                'phone' => '081234567890',
                'role' => 'klien',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Clarin',
                'email' => 'c@c.com',
                'phone' => '081234567890',
                'role' => 'crew_rsvp',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Vegas',
                'email' => 'v@v.com',
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
