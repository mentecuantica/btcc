<?php

namespace Btcc\Providers;

use Btcc\Events\Event;
use Btcc\Services\Subscriptions\Package;
use Btcc\Services\Subscriptions\SubscribeForPackage;
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
        Validator::extend('is_package_exists', function($attribute, $value, $parameters, $validator) {

            $package_ids = app(SubscribeForPackage::class)->getPackagesIds()->toArray();

            return in_array($value,$package_ids);

        });


        Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new Validation($translator, $data, $rules, $messages);
        });

       // $this->initialiszeSubscriptionPackages();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

      // $this->app->register(ViewComposerServiceProvicer::class);




        $this->app->singleton(SubscribeForPackage::class, function($app) {
            $this->mergeConfigFrom(config_path('subscription_packages.php'),'subscription_packages');


            return new SubscribeForPackage(config('subscription_packages.packages'));
        });


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

    /**
    * Customize the subscription plans for the application.
    *
    * @return void
    */
    protected function initialiszeSubscriptionPackages()
    {
       app(SubscribeForPackage::class)->create('Beginner-01','BEG01')
            ->features([
                'Feature 1',
                'Feature 2',
                'Feature 3',
            ]);

        app(SubscribeForPackage::class)->create('Basic', 'BASIC-02')->price(10)
            ->trialDays(7)
            ->features([
                'Feature 1',
                'Feature 2',
                'Feature 3',
            ]);
    }

}
