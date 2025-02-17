<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set Carbon locale to Indonesian
        setlocale(LC_TIME, 'id_ID.utf8', 'id_ID.UTF-8', 'id_ID', 'id');
        Carbon::setLocale('id');
        
        // Set default timezone
        date_default_timezone_set('Asia/Jakarta');
        config(['app.timezone' => 'Asia/Jakarta']);
    }
}
