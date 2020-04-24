<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Token;
use Illuminate\Support\Facades\DB;

class InfoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows info about current player';

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
        $request_token = User::getToken();
        $token = Token::where('token', $request_token)->first();
        if (!$token) {
            $this->error('User is not loging!');
            return;
        }
        $id_user = $token->user_id;

        // Total amount of money
        $money = User::select(['id', 'name', 'money', 'business_points'])->where('id', $id_user)->first()->toArray();
        $this->info('Total amount of money cash: '. $money['money'].'$');

        // List of owned shares
        //  The total cost of all owned shares
        $headers = ['Company Name', 'Share Qty', 'Share Price($)', 'total'];
        $result = DB::table('company_users')
                    ->where('user_id', $id_user)
                    ->join('companies', 'company_users.company_id', '=' ,'companies.id')
                    ->select([
                        'companies.company_name',
                        'company_users.shares_own',
                        'companies.shares_price',
                        DB::raw('(company_users.shares_own * companies.shares_price) as total'),
                    ])
                    ->get();
        $cost = $result->pluck('total')->toArray();
        $array_result = $result->map(function ($item, $key) {
            return (array) $item;
        });
        $this->table($headers, $array_result);

        //  Total cost of all owned shares
        $arr_total = [];
        foreach ($array_result as $item) {
            array_push($arr_total, $item['total']);
        }
        $this->info("Total costs of owned shares: ". array_sum($arr_total));

        //  Business  points
        $this->info('Business points: '. $money['business_points']);
    }
}
