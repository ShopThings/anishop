<?php

namespace App\Console;

use App\Console\Commands\GenerateSitemapCommand;
use App\Jobs\CheckReservedOrdersJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        GenerateSitemapCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // remove expired stored sessions of sanctum
        $schedule->command('sanctum:prune-expired --hours=24')->daily();

        // generate sitemap for both frontend and backend on daily bases
        $schedule->command('app:generate-sitemap')->daily();

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
