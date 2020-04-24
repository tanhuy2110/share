<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PlayerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'player {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays the info about the player';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $headers = ['Company Name', 'Share Qty', 'Share Price($)', 'Total'];
        $name = $this->argument('name');
        $result = DB::table('users')
                    ->where('name', $name)
                    ->join('company_users', 'users.id', '=', 'company_users.user_id')
                    ->join('companies', 'company_users.company_id', '=' ,'companies.id')
                    ->select([
                        'companies.company_name',
                        'company_users.shares_own',
                        'companies.shares_price',
                        DB::raw('(company_users.shares_own * companies.shares_price) as total'),
                        ])
                    ->get();
        $array_result = $result->map(function ($item, $key) {
            return (array) $item;
        });
        $share = $array_result[0]['shares_price'];
        $arr_total = [];
        $this->info("Name: ".  $name);
        $this->table($headers, $array_result);
        foreach ($array_result as $item) {
            array_push($arr_total, $item['total']);
        }
        $this->info("Total costs of owned shares: ". array_sum($arr_total));

    }
}
