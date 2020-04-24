<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Token;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class MemberLoginCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'login {name} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $name = $this->argument('name');
        $password = $this->argument('password');
        //check username
        $validate_name = User::where('name', $name)->first();
        if($validate_name !== NULL) {
            // Check passowrd
            $data = User::where('name', $name)->first();
            if(Hash::check($password,$data->password)){
                $token = User::getToken();
                $exist = Token::where('token', $token)->exists();
                if ($exist) {
                    Token::where('token', $token)->delete();
                }
                Token::create([
                    'user_id' => $data->id,
                    'token' => $token,
                ]);
                // Session::put('name',$data->name);
                // Session::put('login',TRUE);
                $this->info('Login successful');
            }
        }else {
            $this->error('User not available!');
        }
    }
}
