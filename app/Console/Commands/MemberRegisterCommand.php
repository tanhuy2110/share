<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class MemberRegisterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register {name} {password} {passowrdConfirm}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register Member';

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
        $user = $this->argument('name');
        $password = $this->argument('password');
        $password_confirmation = $this->argument('passowrdConfirm');
        if($password == $password_confirmation) {
            $check_user = User::where('name', $user)->first();
            if($check_user == NULL) {
                $register = User::create([
                    'name' => $user,
                    'email' => $user,
                    'password' => bcrypt($password),
                    'money' =>  150.00,
                    'business_points' => 5,
                ]);
                if($register) {
                    $this->info('Register Success!');
                }
            }else {
                $this->error('User available!');
            }
        }else {
            $this->error('Please password and password confirm not true!');
        }
    }
}
