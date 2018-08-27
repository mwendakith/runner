<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\V2\Vl;

class VlFacility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:vl-facility {year?} {--type=3} {--month=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile summary tables for viralload facilities';

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

        $vl = new Vl;

        // $output = $vl->update_facilities($year);
        // $output .= $vl->finish_facilities($year);


        $output="";
        $type = $this->option('type');
        $month = $this->option('month');

        if ($type == 1) {
            $output .= $vl->update_facilities($month, $year);
        }

        if ($type == 2) {
            $output .= $vl->finish_facilities($month, $year);
        }

        if ($type == 3) {
            $output .= $vl->update_facilities($month, $year);
            $output .= $vl->finish_facilities($month, $year);
        }


        $this->info($output);
    }
}
