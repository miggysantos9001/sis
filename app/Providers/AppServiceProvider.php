<?php

namespace App\Providers;

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
    public function boot()
    {
        //$sy_active = \App\Schoolyear::select(['id','name'])->where('isActive',1)->first();
        //view()->share('sy', $sy_active->name);
    }
}
