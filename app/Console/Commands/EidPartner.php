<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Eid;

class EidPartner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:eid-partner {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile summary tables for eid partners';

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

        $output = $eid->update_partners($year);

        $this->info($output);
    }
}
