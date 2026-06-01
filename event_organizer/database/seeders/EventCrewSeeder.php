<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Event;

class EventCrewSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('event_crew')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $event = Event::find(1);
        if (!$event) return;

        $crewUsers = User::where('role', 'crew_eo')->pluck('id', 'name')->toArray();


        $morningJobs = [
            'PL'           => $event->pl_id,
            'Groom'        => 'Mario',
            'Bride'        => 'Eugenia',
            'Family Bride' => 'Kezia',
            'Family Groom' => 'Louisa',
            'Runner'       => 'Alvin',
            'Bridesmaid'   => 'Celine',
            'Groomsman'    => 'Henry',
            'Gereja'       => 'Casey',
            'Loading'      => 'Hanvy',
        ];

        $receptionJobs = [
            'VIP'          => 'Eugenia',
            'Family Groom' => 'Louisa',
            'Family Bride' => 'Kezia',
            'MC'           => 'Casey',
            'MD'           => 'Mario',
            'Lighting'     => 'Marchi',
            'Band'         => 'Alvin',
            'Effect'       => 'Alvin',
            'Banquet'      => 'Henry',
            'Usherettes'   => 'Nathasia',
            'Leader Area'  => 'Jennifer',
            'Area 1'       => 'Raphael',
            'Area 2'       => 'Aprillia',
            'FD 1'         => 'Celine',
            'FD 2'         => 'Christina',
            'Teapai'       => 'Lucy',
        ];

        $slots = [];

        $earnings = [
            'morning' => [],
            'reception' => []
        ];

        foreach ($morningJobs as $job => $name) {
            $userId = ($job === 'PL') ? $name : ($crewUsers[$name] ?? null);
            $fee = 0;

            if ($userId) {
                if (!isset($earnings['morning'][$userId])) {
                    if (in_array($job, ['Bride', 'Runner'])) {
                        $fee = 400000;
                    } else {
                        $fee = 200000;
                    }
                    $earnings['morning'][$userId] = $fee;
                }
            }

            $slots[] = [
                'event_id'   => $event->id,
                'user_id'    => $userId,
                'jobdesk'    => $job,
                'session'    => 'morning',
                'status'     => $userId ? 'Verified' : 'Vacant',
                'fee'        => $fee,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach ($receptionJobs as $job => $name) {
            $userId = $crewUsers[$name] ?? null;
            $fee = 0;

            if ($userId) {
                if (!isset($earnings['reception'][$userId])) {
                    $fee = 200000;
                    $earnings['reception'][$userId] = $fee;
                }
            }

            $slots[] = [
                'event_id'   => $event->id,
                'user_id'    => $userId,
                'jobdesk'    => $job,
                'session'    => 'reception',
                'status'     => $userId ? 'Verified' : 'Vacant',
                'fee'        => $fee,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('event_crew')->insert($slots);
    }
}
