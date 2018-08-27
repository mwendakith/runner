<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\V2\BaseModel;

class SendReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:send {--insert}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Report on the update of the database.';

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
        $insert = $this->option('insert');
        $b = new BaseModel;

        if($insert){
            $b->send_insert_report();
        }
        
        else{
            $b->send_report();            
        }
    }
}
