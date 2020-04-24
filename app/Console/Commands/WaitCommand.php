<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CompanyEvents;

class WaitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wait';

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

            $string_loading = '.';
            $speed = 3; // second
            $wait_to_game_process_end = 5; // second
            // start wait
            $this->output->write($string_loading, false);

            for ($i = 1; $i <= $time; $i++) {
                sleep(1);
                if ($i % $speed == 0) {
                    $this->output->write($string_loading, false);
                }
            }

            // game finish
            sleep($wait_to_game_process_end);
            $this->info('');
            $this->info('Turn finished!');
        } catch (\Exception $e) {
            $this->error('Some thing went wrong!');
        }
    }
}
