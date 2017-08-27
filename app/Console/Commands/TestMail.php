<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Eid;

class TestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Report on the update of the database';

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
        $eid = new Eid;

        $eid->test_mail();
    }
}
