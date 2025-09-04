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
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('pages:import-all')->dailyAt('02:00');
        $schedule->command('characteristics:import')->dailyAt('22:00');
        $schedule->command('characteristics:init-users-subscriptions --since-x-days=3')->dailyAt('22:15');
        $schedule->command('users:sync-on-discourse')->dailyAt('22:30');
        $schedule->command('pages:sync-to-forum')->dailyAt('23:30');
        $schedule->command('pages:import-additional-page-detail')->twiceMonthly();
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
