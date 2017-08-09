<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:all {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile Summary Tables For Eid and Viralload';

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

        $this->info('Updating eid and viralload summary tables for the year ' . $year);

        $this->call('update:eid', [
            'year' => $year
        ]);

        $this->call('update:vl', [
            'year' => $year
        ]);
    }
}
