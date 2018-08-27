<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\V2\Eid;

class EidConfirmatory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:eid-confirmatory {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update eid confirmatory on the samples table.';

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
        $eid = new Eid;

        $year = $this->argument('year');

        $output = $eid->update_confirmatory($year);

        $this->info($output);

    }
}
