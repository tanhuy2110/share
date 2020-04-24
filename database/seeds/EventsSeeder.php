<?php

use Illuminate\Database\Seeder;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'Nothing going on',
            'Technology boom',
            'Energetics crisis',
            'Tourism expansion',
            'Epidemy',
            'Ecology boost',
            'The earthquake',
            'The aliens invasion',
            'The pacifism boost',
            'Economic collapse',
        ];
        $chances = [
            60,
            5,
            5,
            10,
            5,
            4,
            2,
            1,
            5,
            2,
        ];
        $events = [];
        foreach ($names as $key => $name) {
            $events[] = [
                'id' => $key + 1,
                'event_name' => $name,
                'default_chance' => $chances[$key],
            ];
        }
        DB::table('events')->insert($events);
    }
}
