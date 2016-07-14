<?php
/**
 * Author: Yuri Efimov
 * User: octopus
 * Date: 5/27/2016
 * Time: 5:21 AM
 * Filename: ComposerServiceProvicer.php
 */

namespace Btcc\Providers;
use Btcc\Http\ViewComposers\AccountComposer;
use Btcc\Http\ViewComposers\SubmenuComposer;
use Btcc\Services\PackageService;
use Illuminate\Support\ServiceProvider;

/**
 * Class ComposerServiceProvicer
 * @package Btcc\Providers
 */
class ViewComposerServiceProvicer extends ServiceProvider{


    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...



           // view()->composer('*', SubmenuComposer::class);

            view()->composer(
                ['layouts.app','dashboard.index','partner.*'], AccountComposer::class

            );

        // Using Closure based composers...
        view()->composer('dashboard.index', function ($view) {

        });
    }


    public function register()
    {
        // TODO: Implement register() method.
    }

}