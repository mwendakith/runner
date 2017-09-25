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
    protected $signature = 'update:vl {year?} {--type=2} {--month=0}';

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
        $month = $this->option('month');        

        // $this->info('Updating viralload summary tables for the year ' . $year . '\n');

        $this->call('update:vl-nation', [
            'year' => $year, '--month' => $month
        ]);

        $this->call('update:vl-county', [
            'year' => $year, '--month' => $month
        ]);

        $this->call('update:vl-subcounty', [
            'year' => $year, '--month' => $month
        ]);

        $this->call('update:vl-lab', [
            'year' => $year, '--month' => $month
        ]);

        // $this->call('update:vl-lablogs');

        $this->call('update:vl-partner', [
            'year' => $year, '--month' => $month
        ]);
        
        $type = $this->option('type');

        if($type == 2){
            $this->call('update:vl-facility', [
                'year' => $year, '--month' => $month
            ]);
        }
    }
}
