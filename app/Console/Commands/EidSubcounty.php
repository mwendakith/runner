<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\V2\Eid;

class EidSubcounty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:eid-subcounty {year?} {--type=3}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile summary tables for eid subcounty';

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
        $type = $this->option('type');

        $eid = new Eid;
        $output="";

        // $output = $eid->update_subcounties($year);
        // $output .= $eid->update_subcounties_yearly($year);

        if ($type == 1) {
            $output .= $eid->update_subcounties($year);
        }

        else if ($type == 2) {
            $output .= $eid->update_subcounties_yearly($year);
        }

        else{
            $output .= $eid->update_subcounties($year);
            $output .= $eid->update_subcounties_yearly($year);

        }


        $this->info($output);
    }
}
