<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
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
        // Pagination habillée avec les styles du thème.
        Paginator::defaultView('vendor.pagination.theme');
        Paginator::defaultSimpleView('vendor.pagination.theme');

        // Dates et nombres en français sur tout le site.
        Carbon::setLocale(config('app.locale'));
        setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR', 'french');

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
