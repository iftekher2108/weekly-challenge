<?php

namespace App\Providers;

use App\Models\task;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

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
        // Remove the X-Powered-By header from all responses
        Response::macro('removePoweredBy', function ($response) {
            $response->headers->remove('X-Powered-By');
            return $response;
        });

        task::overdueLastWeek()->update([
        'status' => 'not_completed',
        ]);



       Paginator::useBootstrapFive();
    }
}
