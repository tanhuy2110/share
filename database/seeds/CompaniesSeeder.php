<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'MicroHARD',
            'Poisonic',
            'anYWay',
            'HUT-Build',
            'HARMORY',
            'X-Tray',
            'ZeroStar',
            'plut-ONION',
            'Newlegs',
            'TryArm',
        ];
        $companies = [];
        foreach ($names as $key => $name) {
            $companies[] = [
                'id' => $key + 1,
                'company_name' => $name,
                'shares_code' => Str::upper(Str::random(6)),
                'shares_qty' => 1000,
                'shares_price' => 10,
            ];
        }
        DB::table('companies')->insert($companies);
    }
}
