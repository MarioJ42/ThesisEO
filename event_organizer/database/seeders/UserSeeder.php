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
                'role' => 'owner',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mario',
                'email' => 'm@m.com',
                'role' => 'pl',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Kezia',
                'email' => 'k@k.com',
                'role' => 'klien',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sharon',
                'email' => 's@s.com',
                'role' => 'crew_rsvp',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Vegas',
                'email' => 'v@v.com',
                'role' => 'crew_eo',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('users')->insert($users);
    }
}
