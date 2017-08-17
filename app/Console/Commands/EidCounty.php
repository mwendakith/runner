<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Eid;

class EidCounty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:eid-county {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile summary tables for eid counties';

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

        $eid = new Eid;

        $output = $eid->update_counties($year);
        $output .= $eid->update_counties_yearly($year);

        $this->info($output);
    }
}
