<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CompaniesSeeder::class,
            BusinessSeeder::class,
            BusinessCompanySeeder::class,
            EventsSeeder::class,
            BusinessEventsSeeder::class,
        ]);
    }
}
