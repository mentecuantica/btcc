<?php

namespace Btcc\Providers;

use Btcc\Http\Guard\SentinelGuard;
use Cartalyst\Sentinel\Sentinel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'Btcc\Model' => 'Btcc\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {

        /*Auth::provider('our_provider', function() {
           return new Sentinel();
        });*/
        $this->registerPolicies($gate);

        //
    }
}
