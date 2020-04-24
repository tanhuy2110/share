<?php

use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'Technologies',
            'Pharmaceuticals',
            'Tourism',
            'Real estate (building)',
            'Military',
        ];
        $business = [];
        foreach ($names as $key => $name) {
            $business[] = [
                'id' => $key + 1,
                'career' => $name,
            ];
        }
        DB::table('business')->insert($business);
    }
}
