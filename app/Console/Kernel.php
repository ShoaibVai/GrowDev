<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\SendTaskReminders::class,
        \App\Console\Commands\SendNotificationDigests::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // send reminders daily; digests processed hourly
        $schedule->command('tasks:send-reminders')->daily();
        $schedule->command('notifications:send-digests')->hourly();
    }

    protected function commands(): void
    {
        // Load other commands if needed
    }
}
