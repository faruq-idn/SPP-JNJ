<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * These schedules are run in the background and do not affect the user experience.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected $commands = [
        Commands\GenerateTagihanBulanan::class
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('tagihan:generate')
            ->monthlyOn(1, '00:00')
            ->appendOutputTo(storage_path('logs/scheduler.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
