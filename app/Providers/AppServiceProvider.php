<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
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

        // Formulaires publics (don, contact, commentaire…) : freine le spam et les envois en rafale.
        RateLimiter::for('forms', fn (Request $request) => [
            Limit::perMinute(5)->by('minute:'.$request->ip()),
            Limit::perDay(50)->by('day:'.$request->ip()),
        ]);
    }
}
