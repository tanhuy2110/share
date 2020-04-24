<?php

use Illuminate\Database\Seeder;

class BusinessEventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [ 'business_id' => 1, 'event_id' => 1, 'type' => 0, ],
            [ 'business_id' => 2, 'event_id' => 1, 'type' => 0, ],
            [ 'business_id' => 3, 'event_id' => 1, 'type' => 0, ],
            [ 'business_id' => 4, 'event_id' => 1, 'type' => 0, ],
            [ 'business_id' => 5, 'event_id' => 1, 'type' => 0, ],
            [ 'business_id' => 1, 'event_id' => 2, 'type' => 1, ],
            [ 'business_id' => 1, 'event_id' => 3, 'type' => -1, ],
            [ 'business_id' => 3, 'event_id' => 4, 'type' => 1, ],
            [ 'business_id' => 3, 'event_id' => 5, 'type' => -1, ],
            [ 'business_id' => 2, 'event_id' => 5, 'type' => 1, ],
            [ 'business_id' => 2, 'event_id' => 6, 'type' => -1, ],
            [ 'business_id' => 3, 'event_id' => 6, 'type' => 1, ],
            [ 'business_id' => 2, 'event_id' => 7, 'type' => 1, ],
            [ 'business_id' => 1, 'event_id' => 7, 'type' => -1, ],
            [ 'business_id' => 3, 'event_id' => 7, 'type' => -1, ],
            [ 'business_id' => 4, 'event_id' => 7, 'type' => -1, ],
            [ 'business_id' => 5, 'event_id' => 7, 'type' => -1, ],
            [ 'business_id' => 5, 'event_id' => 8, 'type' => 1, ],
            [ 'business_id' => 1, 'event_id' => 8, 'type' => 1, ],
            [ 'business_id' => 2, 'event_id' => 8, 'type' => 1, ],
            [ 'business_id' => 3, 'event_id' => 8, 'type' => 1, ],
            [ 'business_id' => 4, 'event_id' => 8, 'type' => 1, ],
            [ 'business_id' => 5, 'event_id' => 9, 'type' => -1, ],
            [ 'business_id' => 1, 'event_id' => 10, 'type' => -1, ],
            [ 'business_id' => 2, 'event_id' => 10, 'type' => -1, ],
            [ 'business_id' => 3, 'event_id' => 10, 'type' => -1, ],
            [ 'business_id' => 4, 'event_id' => 10, 'type' => -1, ],
            [ 'business_id' => 5, 'event_id' => 10, 'type' => -1, ],
        ];
        DB::table('business_events')->insert($data);
    }
}
