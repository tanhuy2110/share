<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class PlayersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'players';

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
        $headers = ['Id', 'User'];
        $list_player = User::select(['id', 'name'])->get();
        $this->table($headers, $list_player);
    }
}
