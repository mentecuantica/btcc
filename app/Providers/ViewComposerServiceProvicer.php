<?php
/**
 * Author: Yuri Efimov
 * User: octopus
 * Date: 5/27/2016
 * Time: 5:21 AM
 * Filename: ComposerServiceProvicer.php
 */

namespace Btcc\Providers;

use Btcc\Http\ViewComposers\UserInfoComposer;
use Btcc\Http\ViewComposers\SubmenuComposer;
use Btcc\Services\PackageService;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

/**
 * Class ComposerServiceProvicer
 * @package Btcc\Providers
 */
class ViewComposerServiceProvicer extends ServiceProvider {

    /**
     * Register bindings in the container.
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        $packageService = app(PackageService::class);

        // view()->composer('*', SubmenuComposer::class);

        view()->composer(['layouts.app'], UserInfoComposer::class);

  //      dd($packageService->getPackagesIds());



        // Using Closure based composers...
        view()->composer(['dashboard.index','partner.create'], function (View $view) use ($packageService) {



            $view->with(['packages'=>$packageService]);
        });
    }

    public function register()
    {
        // TODO: Implement register() method.
    }

}