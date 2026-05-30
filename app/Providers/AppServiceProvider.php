<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        Paginator::useBootstrapFive();
        //Cambiamos a 'pinggy-free.link' para que coincida exactamente con tu túnel
        // if (str_contains(request()->getHost(), 'pinggy-free.link')) {
        //     \Illuminate\Support\Facades\URL::forceScheme('https');
        // }
    }
}
