<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\V2\Vl;

class VlConfirmatory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:vl-confirmatory {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update vl confirmatory on samples table.';

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
        $vl = new Vl;

        $year = $this->argument('year');

        $output = $vl->update_confirmatory($year);

        $this->info($output);

    }
}
