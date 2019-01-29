<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\V2\Vl;

class VlPoc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:vl-poc {year?} {--month=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile summary tables for viralload poc';

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
        $year = $this->argument('year');
        $month = $this->option('month');

        $vl = new Vl;

        $output = $vl->update_poc($month, $year);
        $output .= $vl->finish_poc($month, $year);

        $this->info($output);
    }
}
