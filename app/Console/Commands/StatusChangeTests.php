<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class StatusChangeTests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:chnageTests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update training status based on start and end date times';

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
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();

        // Update ongoing tests
        DB::table('tests')
            ->where('start_date_time', '<=', $now)
            ->where('end_date_time', '>=', $now)
            ->update(['status' => 0]);

        // Update upcoming tests
        DB::table('tests')
            ->where('start_date_time', '>', $now)
            ->update(['status' => 1]);

        // Update complated/expired tests
        DB::table('tests')
            ->where('end_date_time', '<', $now)
            ->update(['status' => 1]);

        return 0;
    }
}
