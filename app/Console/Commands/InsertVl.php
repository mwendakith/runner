<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\EidInsert;


class InsertVl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:vl {year?} {--type=3} {--month=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert values for vl tables at the beginning of the month.';

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

        $vl = new VlInsert;
        $output="";
        $type = $this->option('type');
        $month = $this->option('month');

        if ($type == 1) {
            for ($i=1; $i < 13; $i++) { 
                 $output .= $vl->rejections($year, $i);
            }
        }

        else if ($type == 2) {
            $output .= $vl->rejections($year, $month);
        }

        else{
            $output .= $vl->rejections();
        }

        $this->info($output);
    }
}
