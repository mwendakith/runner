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
    }
}
