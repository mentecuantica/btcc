<?php

namespace Btcc\Providers;

use Btcc\Events\Event;
use Btcc\Services\SystemWallet;
use Btcc\Services\Validation;
use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('foo', function($attribute, $value, $parameters, $validator) {
            return $value == 'foo';
        });

        Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new Validation($translator, $data, $rules, $messages);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->register(ViewComposerServiceProvicer::class);
        
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
