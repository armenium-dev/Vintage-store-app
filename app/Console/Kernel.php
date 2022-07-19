<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ShopifySync;

class Kernel extends ConsoleKernel{

    protected $commands = [
        Commands\Depop::class,
        Commands\Asos::class,
        Commands\ShopifySync::class,
        Commands\ShopifyProducts::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule){
        #$schedule->command('depop:run')->everyMinute();
        #$schedule->command('asos:run')->everyMinute();
        $schedule->command('shopifyproducts:run')->everyMinute();
        $schedule->command('shopifysync:run')->everyTwoMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(){
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
