<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// Optional: uncomment if you need the 191 length fix for older MySQL versions
// use Illuminate\Support\Facades\Schema;

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
        // If you're on older MySQL/MariaDB and hit index length errors, uncomment:
        // Schema::defaultStringLength(191);
    }
}
