<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CompanyEvents;

class TimeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'time';

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
        try {
            // caculate time to end game
            $time = 0; // second
            // get last time of event
            $last_event = CompanyEvents::orderBy('created_at', 'desc')->first();
            $last_time = $last_event->created_at;


            if ($last_time) {
                // get diff time of last event to new event
                $time = \Carbon\Carbon::now()->diffInSeconds($last_time->addMinutes(5));
            }

            // $wait1 =  gmdate("i.s", $time);
            // $this->info($wait1);
            $minutes = floor($time/60);
            $secondsleft = $time%60;
            if($minutes<10)
                $minutes = "0" . $minutes;
            if($secondsleft<10)
                $secondsleft = "0" . $secondsleft;
            $wait = "$minutes:$secondsleft Sec";
            $this->info('Time before the current turn is finished: '.$wait);
        } catch (\Exception $e) {
            $this->error('Some thing went wrong!');
        }
    }
}
