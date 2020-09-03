<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Send all outputs to stdout using its alias
        $out = '/proc/1/fd/1';

        $schedule->command(Commands\CalculateInvestorsClaims::class)
            ->hourly()
            ->withoutOverlapping()
            ->onOneServer()
            ->appendOutputTo($out);

        $schedule->command(Commands\CalculateCommissions::class)
            ->hourly()
            ->withoutOverlapping()
            ->onOneServer()
            ->appendOutputTo($out);

        $schedule->command(Commands\CreateContractPdfs::class)
            ->hourly()
            ->withoutOverlapping()
            ->onOneServer()
            ->appendOutputTo($out);

        $schedule->command(Commands\CreateBillsPdfs::class)
            ->hourly()
            ->withoutOverlapping()
            ->onOneServer()
            ->appendOutputTo($out);

        $schedule->command(Commands\SendBillMails::class)
            ->hourly()
            ->onOneServer()
            ->appendOutputTo($out);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
