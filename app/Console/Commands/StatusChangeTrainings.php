<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class StatusChangeTrainings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:chnageTraining';

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

        // Update ongoing  trainings
        DB::table('trainings')
            ->where('start_date_time', '<=', $now)
            ->where('end_date_time', '>=', $now)
            ->update(['status' => 1]);

        // Update upcoming trainings
        DB::table('trainings')
            ->where('start_date_time', '>', $now)
            ->update(['status' => 0]);

        // Update Complated/Expired trainings
        DB::table('trainings')
            ->where('end_date_time', '<', $now)
            ->update(['status' => 2]);

        return 0;
    }

}
