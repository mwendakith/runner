<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\V2\Vl;

class VlSuppression extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:vl-suppression';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update vl site suppression table.';

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
        $vl = new Vl;

        $output = $vl->update_suppression();

        $this->info($output);

    }
}
