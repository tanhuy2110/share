<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\User;
use App\Models\Company;
use App\Models\CompanyUsers;
use Carbon\Carbon;
use DB;

class CreateEventCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create new event';

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
        $percent = rand(2, 10);
        DB::beginTransaction();
        try {
            // $query = "SELECT *, default_chance >= RAND() * ( SELECT max(default_chance ) FROM events ) FROM events
            //     WHERE default_chance >= RAND() * ( SELECT max(default_chance ) FROM events )
            //     ORDER BY default_chance LIMIT 1;";

            $events = Event::get();
            // get random event
            $array = [];
            foreach ($events as $event) {
                foreach (range(1, $event->default_chance) as $i) {
                    $array[] = $event->id;
                }
            }
            // random event id
            $id = $array[array_rand($array)];
            // get event via event id
            $event = Event::with(['business' => function ($query) {
                $query->with('companies');
            }])->find($id);

            // create events that impact the company
            $companyEvents = [];
            foreach ($event->business as $business) {
                foreach ($business->companies as $company) {
                    $newPrice = 0;
                    if ($business->pivot->type == 1) {
                        $newPrice = $company->shares_price * (1 + ($percent / 100));
                    } else if($business->pivot->type == -1){
                        $newPrice = $company->shares_price * (1 - ($percent / 100));
                    } else {
                        $newPrice = $company->shares_price;
                    }
                    // update share price
                    $company->update([
                        'shares_price' => $newPrice,
                    ]);
                    $companyEvents[] = [
                        'company_id' => $company->id,
                        'chance' => $percent,
                        'type' => $business->pivot->type,
                        'price' => $newPrice,
                    ];
                }
            }

            // Update Business points
            $data_user = User::get();
            $arr_id_user = '';

            foreach ($data_user as $item) {
                // Player money
                $money = User::select(['money'])->where('id', $item->id)->first()->toArray();
                // $this->info($money['money']);

                // The higher share cost

                $result = DB::table('company_users')
                    ->where('user_id', $item->id)
                    ->join('companies', 'company_users.company_id', '=' ,'companies.id')
                    ->select([
                        'companies.company_name',
                        'company_users.shares_own',
                        'companies.shares_price',
                        DB::raw('(company_users.shares_own * companies.shares_price) as total'),
                    ])
                    ->get();
                $cost = $result->pluck('total')->toArray();
                $arr_total = [];
                $array_result = $result->map(function ($item, $key) {
                    return (array) $item;
                });
                foreach ($array_result as $value) {
                    array_push($arr_total, $value['total']);
                    // $this->info($value['total']);
                }
                if (count($cost) > 0) {
                    User::where('id', $item->id)->update([
                        'business_points' => round($money['money'] / max($cost)),
                        'money' => $money['money'] - (array_sum($arr_total)*0.05)
                    ]);
                }

            }
            $event->companyEvents()->createMany($companyEvents);
            // $this->table(['id', 'event_name', 'default_chance', 'created_at', 'updated_at'], $event);

            // check player does nothing (no selling or buying) for three turns in a row
            $companyUsers = CompanyUsers::select([
                'user_id',
                DB::raw('max(updated_at) as updated_at'),
            ])
            ->groupBy(['user_id'])
            ->get();

            foreach ($companyUsers as $companyUser) {
                $diff = $companyUser->updated_at->diffInMinutes($event->created_at);
                // $this->info($diff);
                $max_minute = 15; // 3 turn = 15 minutes
                if ($diff > $max_minute) {
                    $user = User::with('companyUsers')->find($companyUser->user_id);
                    $this->info($user->name . ' lost the game!');
                    // shares returned to the companies
                    foreach ($user->companyUsers as $userCompanyUser) {
                        $model = Company::find($userCompanyUser->company_id);
                        $model->shares_qty += $userCompanyUser->shares_own;
                        $model->save();
                    }

                    // clear user shares
                    CompanyUsers::where('user_id', $companyUser->user_id)->delete();

                    // reset user money
                    $default_money = 150;
                    $model = User::find($companyUser->user_id);
                    $model->money = $default_money;
                    $model->business_points = 5;
                    $model->save();
                }
            }

            // // if shares were sold
            // // case 1 company shares were sold
            // if ($company_share_rest == 0) {
            //     $user = User::join('company_users', 'company_users.user_id', '=', 'users.id')
            //         ->where('company_id', $company->id)
            //         ->orderBy('shares_own', 'desc')
            //         ->select([
            //             'users.id',
            //             'users.name',
            //             'company_users.shares_own',
            //             'company_users.company_id',
            //         ])
            //         ->first();
            //     $this->info('The winner is: ' . $user->name);
            // }

            // case all company shares were sold
            $max_qty = Company::select(DB::raw('max(shares_qty) as max'))->first();
            if ($max_qty->max == 0) {
                $user = User::join('company_users', 'company_users.user_id', '=', 'users.id')
                    ->orderBy('shares_own', 'desc')
                    ->select([
                        'users.id',
                        'users.name',
                        DB::raw('sum(company_users.shares_own)'),
                    ])
                    ->groupBy([
                        'users.id',
                        'users.name',
                    ])
                    ->first();
                $this->info('The winner is: ' . $user->name);
            }

            DB::commit();
            $this->info("Event " . $event->event_name . " Complete!");
            $this->info("Time " . Carbon::now());
        } catch (\Exception $e) {
            DB::rollback();
            $this->error("Create event false!");
            $this->error($e->getMessage());
        }

    }
}
