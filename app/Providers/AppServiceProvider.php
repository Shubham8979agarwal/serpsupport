<?php

namespace App\Providers;

#use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;


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

    public function boot()
    {
        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }

        /*if (env('APP_ENV') === 'local'){
            Artisan::call('view:clear');
        }*/
    }
}
