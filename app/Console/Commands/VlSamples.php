<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\V2\VlOther;

class VlSamples extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:vl-samples';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update vl samples table.';

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
        $vl = new VlOther;

        $output = $vl->update_all();

        $this->info($output);
    }
}
