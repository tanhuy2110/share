<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CompanyEvents;

class HistoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'history {count}';

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
        $headers = ['Event Name', 'Create At'];
        $count = $this->argument('count');
        $result = CompanyEvents::join('events', 'events.id', '=', 'company_events.event_id')
        ->select(['events.event_name', 'company_events.created_at'])
        ->distinct('company_events.event_id', 'company_events.created_at')
        ->limit($count)
        ->orderBy('company_events.created_at', 'DESC')
        ->get();
        $this->table($headers, $result);
    }
}
