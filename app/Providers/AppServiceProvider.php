<?php

namespace Btcc\Providers;

use Btcc\Services\SystemWallet;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->register(ComposerServiceProvicer::class);
        
        $this->app->singleton('SystemWallet', function ($app) {
            return new SystemWallet(1250);
        });

        /**
         * For dev only
         */
        if ($this->app->environment() == 'local') {
            $this->app->register(\Laracasts\Generators\GeneratorsServiceProvider::class);
            $this->app->register(\Clockwork\Support\Laravel\ClockworkServiceProvider::class);

            
            $this->app->alias('Clockwork',\Clockwork\Support\Laravel\Facade::class);
        }
    }
}
