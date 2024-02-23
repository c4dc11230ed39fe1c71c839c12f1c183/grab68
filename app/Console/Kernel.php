<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        // ! Modify the schedule to run the Laravel schedule every 1 minutes
        // ! This must be included to overcome hosting provider's cron job limitation
        $schedule->call('\EiichiroOda\OpGrab68\Controllers\OpCron68Controller@autoGrab68Job')->everyFifteenMinutes()->name('auto-grab-68-job')->withoutOverlapping();
        $schedule->call('\EiichiroOda\OpGrab68\Controllers\OpGrab68Controller@tyGia68AnalyzeV1')->everyFifteenSeconds()->name('ty-gia-68-analyzer')->withoutOverlapping();
        $schedule->call('\EiichiroOda\OpGrab68\Controllers\OpGrab68Controller@getTyGia68MarketPrice')->everyMinute()->name('ty-gia-68-market-price')->withoutOverlapping();
        $schedule->call('\EiichiroOda\OpGrab68\Controllers\OpGrab68Controller@getTyGia68EmoneyPrice')->everyMinute()->name('ty-gia-68-emoney-price')->withoutOverlapping();
        $schedule->call('\EiichiroOda\OpGrab68\Controllers\OpGrab68Controller@getTyGia68UsdtPrice')->everyMinute()->name('ty-gia-68-usdt-price')->withoutOverlapping();
        $schedule->call('\EiichiroOda\OpGrab68\Controllers\OpGrab68Controller@getTyGia68GoldPrice')->everyFiveMinutes()->name('ty-gia-68-gold-price')->withoutOverlapping();
        $schedule->call('\EiichiroOda\OpGrab68\Controllers\OpGrab68Controller@getTyGia68NicePrice')->everyFiveMinutes()->name('ty-gia-68-nice-price')->withoutOverlapping();
        $schedule->call('\EiichiroOda\OpGrab68\Controllers\OpGrab68Controller@getTyGia68VcbPrice')->everyFiveMinutes()->name('ty-gia-68-vcb-price')->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
