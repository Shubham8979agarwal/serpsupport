<?php

namespace App\Providers;

#use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    /*public function boot(): void
    {
        \URL::forceScheme('https');
    }*/

    /*public function boot(): void
    {
        if (env('APP_ENV') == 'production') {
            \URL::forceScheme('https');
        }
    }*/
}
