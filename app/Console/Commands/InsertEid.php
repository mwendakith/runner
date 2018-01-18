<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\EidInsert;


class InsertEid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:eid {year?} {--type=3} {--month=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert values for eid tables at the beginning of the month.';

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
        $year = $this->argument('year');

        $eid = new EidInsert;
        $output="";
        $type = $this->option('type');
        $month = $this->option('month');

        if ($type == 0) {
            $output .= $eid->summary_yearly($year);
        }

        else if ($type == 1) {
            // $bar = $this->output->createProgressBar(12);
            for ($i=1; $i < 13; $i++) { 
                $output .= $eid->insert_lab_mapping($year, $i);
                // $output .= $eid->summary($year, $i);
                // $bar->advance();
            }
            // $output .= $eid->summary_yearly($year);
            // $bar->finish();
        }


        else if ($type == 2) {
            $output .= $eid->summary($year, $month);
        }

        else if ($type == 3) {
            $output .= $eid->insert_lab_mapping();
            // $output .= $eid->summary();
        }

        $this->info($output);
    }
}
