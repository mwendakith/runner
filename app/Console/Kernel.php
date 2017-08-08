<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        Commands\UpdateEid::class,
        Commands\UpdateVl::class,

        Commands\EidNation::class,
        Commands\EidCounty::class,
        Commands\EidSubcounty::class,
        Commands\EidFacility::class,
        Commands\EidLab::class,
        Commands\EidPartner::class,

        Commands\VlNation::class,
        Commands\VlCounty::class,
        Commands\VlSubcounty::class,
        Commands\VlFacility::class,
        Commands\VlLab::class,
        Commands\VlPartner::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
