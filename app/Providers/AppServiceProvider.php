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
   /**
    * The boot function sets the default pagination view to bootstrap-5 and forces the URL scheme to
    * HTTPS in production environment.
    */
    public function boot(): void
    {        // dd($this->app->environment());
        Paginator::defaultView('pagination::bootstrap-5');
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
        // \URL::forceScheme(env('APP_PROTOCOL'));
    }
}
