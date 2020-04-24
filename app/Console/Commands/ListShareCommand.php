<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;

class ListShareCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows the list of available shares';

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
        $headers = ['Id', 'Name Company', 'Share Code', 'Share Qty', 'Share Price'];
        $list_share = Company::select(['id', 'company_name', 'shares_code', 'shares_qty', 'shares_price'])->get();
        $this->table($headers, $list_share);
    }
}
