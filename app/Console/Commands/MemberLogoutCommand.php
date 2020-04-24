<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Token;

class MemberLogoutCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Logout';

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
        //
        // Session::flush();
        $token = User::getToken();
        $exist = Token::where('token', $token)->exists();
        if ($exist) {
            Token::where('token', $token)->delete();
        }

        $this->info('User logged out');
    }
}
