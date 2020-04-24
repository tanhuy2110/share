<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CompanyUsers;
use App\Models\Company;
use App\Models\User;
use App\Models\Token;
use Illuminate\Support\Facades\DB;

class SellShareCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sell {share} {count}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sell Share';

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
            $this->error('User not logged in!');
            return;
        }
        $user_id = $token->user_id;

        $share = $this->argument('share');
        $count = $this->argument('count');

        $company_id = Company::select(['id'])->where('shares_code', $share)->get();
        $company_share_qty = Company::select(['shares_qty'])->where('shares_code', $share)->get();
        $company_share_price = Company::select(['shares_price'])->where('shares_code', $share)->get();
        // Check share available
        $check_share = DB::table('company_users')
                        ->where('company_id', $company_id[0]['id'])
                        ->get();

        $array_check_share = $check_share->map(function ($item, $key) {
            return (array) $item;
        });
        $amount_current_user = User::select(['money'])->where('id', $user_id)->get();

        // check business points
        $data_user = User::select(['business_points'])->where('id', $user_id)->first()->toArray();;
        $business_points = $data_user['business_points'];

        if ($business_points > 0) {
            if($array_check_share[0]['id'] !== null) {
                DB::beginTransaction();
                try {
                    // Get share of user
                    $data_share_user = CompanyUsers::where('company_id', $company_id[0]['id'])->where('user_id', $user_id)->get();
                    $share_current_user = $data_share_user[0]['shares_own'];

                    if($share_current_user >= $count) {
                        // User sell share
                        $sell = CompanyUsers::where('company_id', $company_id[0]['id'])->where('user_id', $user_id)->update([
                            'shares_own' => $share_current_user - $count
                        ]);

                        Company::where('id', $company_id[0]['id'])->update([
                            'shares_qty' => $company_share_qty[0]['shares_qty'] + $count
                        ]);

                        User::where('id', $user_id)->update([
                            'money' => $amount_current_user[0]['money'] + ($company_share_price[0]['shares_price']*$count),
                            'business_points' => $business_points -1
                        ]);
                        $this->info('Sell Complete');
                    } else {
                        $this->error('You have: '. $share_current_user.' this company share');
                    }

                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();

                    throw new Exception($e->getMessage());
                }
            } else {
                $this->info('Ban khong co co phieu nay!');
            }
        } else {
            $this->error('You cant sell. Because you dont have business points!');
        }
    }
}
