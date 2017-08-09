<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateEid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:eid {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile Summary Tables For Eid';

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

        $this->info('Updating eid summary tables for the year ' . $year);

        $this->call('update:eid-nation', [
            'year' => $year
        ]);

        $this->call('update:eid-county', [
            'year' => $year
        ]);

        $this->call('update:eid-subcounty', [
            'year' => $year
        ]);

        $this->call('update:eid-lab', [
            'year' => $year
        ]);

        $this->call('update:eid-partner', [
            'year' => $year
        ]);

        $this->call('update:eid-facility', [
            'year' => $year
        ]);
    }
}
