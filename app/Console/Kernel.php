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
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('check:invoices:status')->daily()->withoutOverlapping();
        $schedule->command('check:estimates:status')->daily()->withoutOverlapping();
        $schedule->command('auto:archive:invoices')->daily()->withoutOverlapping();
        $schedule->command('auto:archive:estimates')->daily()->withoutOverlapping();
        $schedule->command('auto:archive:payments')->daily()->withoutOverlapping();
        $schedule->command('recurring:invoices')->daily()->withoutOverlapping();
        $schedule->command('reminder:due:invoices')->daily()->withoutOverlapping();
        $schedule->command('reminder:overdue:invoices')->daily()->withoutOverlapping();
        $schedule->command('expiring_subscription:reminder:due')->daily()->withoutOverlapping();
        $schedule->command('expiring_subscription:reminder:overdue')->daily()->withoutOverlapping();
        $schedule->command('expiring_trials:reminder')->daily()->withoutOverlapping();
        $schedule->command('recurring:expenses')->daily()->withoutOverlapping();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
