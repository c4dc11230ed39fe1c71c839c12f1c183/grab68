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
        $schedule->command('\App\Http\Controllers\Grab68Controller@getTyGia68MarketPrice')->everyMinute()->name('ty-gia-68-market-price')->withoutOverlapping();
        $schedule->command('\App\Http\Controllers\Grab68Controller@getTyGia68EmoneyPrice')->everyMinute()->name('ty-gia-68-emoney-price')->withoutOverlapping();
        $schedule->command('\App\Http\Controllers\Grab68Controller@getTyGia68UsdtPrice')->everyMinute()->name('ty-gia-68-usdt-price')->withoutOverlapping();
        $schedule->command('\App\Http\Controllers\Grab68Controller@getTyGia68GoldPrice')->everyFiveMinutes()->name('ty-gia-68-gold-price')->withoutOverlapping();
        $schedule->command('\App\Http\Controllers\Grab68Controller@getTyGia68NicePrice')->everyFiveMinutes()->name('ty-gia-68-nice-price')->withoutOverlapping();
        $schedule->command('\App\Http\Controllers\Grab68Controller@getTyGia68VcbPrice')->everyFiveMinutes()->name('ty-gia-68-vcb-price')->withoutOverlapping();
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
