<?php

use Illuminate\Database\Seeder;

class BusinessCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [ 'company_id' => 1, 'business_id' => 1, ],
            [ 'company_id' => 2, 'business_id' => 2, ],
            [ 'company_id' => 3, 'business_id' => 3, ],
            [ 'company_id' => 4, 'business_id' => 4, ],
            [ 'company_id' => 5, 'business_id' => 5, ],
            [ 'company_id' => 6, 'business_id' => 1, ],
            [ 'company_id' => 6, 'business_id' => 2, ],
            [ 'company_id' => 7, 'business_id' => 3, ],
            [ 'company_id' => 7, 'business_id' => 4, ],
            [ 'company_id' => 8, 'business_id' => 5, ],
            [ 'company_id' => 8, 'business_id' => 1, ],
            [ 'company_id' => 9, 'business_id' => 5, ],
            [ 'company_id' => 9, 'business_id' => 2, ],
            [ 'company_id' => 10, 'business_id' => 5, ],
            [ 'company_id' => 10, 'business_id' => 3, ],
        ];
        DB::table('business_company')->insert($data);
    }
}
