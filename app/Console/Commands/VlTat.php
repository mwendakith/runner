<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Vl;

class VlTat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:vl-tat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update vl tat on samples table.';

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

        $output = $vl->update_tat();

        $this->info($output);

        $this->call('report:send');
    }
}
