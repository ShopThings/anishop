<?php

namespace App\Console;

use App\Jobs\CheckReservedOrdersJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // remove expired stored sessions of sanctum
        $schedule->command('sanctum:prune-expired --hours=24')->daily();

        // check reserved orders and take action
        $schedule->job(new CheckReservedOrdersJob)->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
