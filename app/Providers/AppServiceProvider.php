<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

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
    //   try {
    //         DB::connection()->getPdo();
    //     } catch (\Exception $e) {
    //         echo "❌ [DATABASE ERROR] MySQL is not running or .env config is invalid.\n";
    //         echo "Reason: " . $e->getMessage() . "\n";
    //         exit(1);
    //     }
    }
}
