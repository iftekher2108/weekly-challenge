<?php

namespace App\Providers;

use App\Models\task;
use Illuminate\Pagination\Paginator;
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
        task::overdueLastWeek()->update([
        'status' => 'progress',
        ]);



       Paginator::useBootstrapFive();
    }
}
