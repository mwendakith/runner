<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateVl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:vl {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile Summary Tables For Viralload';

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

        $this->info('Updating viralload summary tables for the year ' . $year);

        $this->call('update:vl-nation', [
            'year' => $year
        ]);

        $this->call('update:vl-county', [
            'year' => $year
        ]);

        $this->call('update:vl-subcounty', [
            'year' => $year
        ]);

        $this->call('update:vl-lab', [
            'year' => $year
        ]);

        $this->call('update:vl-partner', [
            'year' => $year
        ]);
        
        $this->call('update:vl-facility', [
            'year' => $year
        ]);
    }
}
