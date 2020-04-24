<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\User;
use App\Models\CompanyUsers;
use App\Models\Token;
use Illuminate\Support\Facades\DB;

class BuyShareCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buy {share} {count}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Buy Share';

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

        $company_id = Company::select(['id'])->where('shares_code', $share)->first();
        // $this->info($company_id['id']);
        if (!$company_id) {
            $this->error('The shares is not exist!');
            return;
        }

        // Update User Money
        $price_share = Company::select(['shares_price'])->where('shares_code', $share)->get();
        $member_money = User::select(['money'])->where('id', $user_id)->get();
        $money_used = $price_share[0]['shares_price']*$count;

        // check business points
        $data_user = User::select(['business_points'])->where('id', $user_id)->first()->toArray();;
        $business_points = $data_user['business_points'];

        $company_share_qty = Company::select(['shares_qty'])->where('shares_code', $share)->get();
        if ($business_points > 0) {
            if($member_money[0]['money'] < $money_used) {
                $this->error('You dont enough money!');
            }else {

                if( $company_share_qty[0]['shares_qty'] >= $count) {
                    DB::beginTransaction();
                    try {
                        // // $company_share_qty = Company::select(['shares_qty'])->where('shares_code', $share)->first()->toArray();
                        // $company_share_rest = $company_share_qty[0]['shares_qty'] - $count;

                        // // if shares is not enough
                        // if ($company_share_rest < 0) {
                        //     $this->error('Shares is not enough!');
                        //     return;
                        // }

                        $data_company_user = CompanyUsers::select(['id', 'company_id', 'user_id', 'shares_own'])
                                            ->where('company_id', $company_id['id'])
                                            ->where('user_id', $user_id)
                                            ->first();

                        if($data_company_user) {
                            $buy = CompanyUsers::where('company_id', $company_id['id'])
                                                    ->where('user_id', $user_id)
                                                    ->update(['shares_own' => $data_company_user['shares_own'] + $count]);
                        } else {
                            $buy = CompanyUsers::create([
                                'company_id' => $company_id['id'],
                                'user_id' => $user_id,
                                'shares_own' => $count
                            ]);
                        }

                        // Update Company Share

                        $company_share_rest = $company_share_qty[0]['shares_qty'] - $count;
                        Company::where('shares_code', $share)->update(['shares_qty' => $company_share_rest]);

                        // Update User Money
                        User::where('id', $user_id)->update([
                            'money' => $member_money[0]['money'] - $money_used,
                            'business_points' => $business_points -1
                        ]);
                        $this->info('Buy Share Complete!');
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();

                        throw new Exception($e->getMessage());
                    }
                } else {
                    $this->error('This company is stock is not enough');
                }
            }
        } else {
            $this->error('You cant buy. Because you dont have business points!');
        }
    }
}
