<?php

namespace Btcc\Providers;

use Btcc\Events\ProfileWasUpdated;
use Btcc\Events\UserRegisteredPartner;
use Btcc\Listeners\ProfileUpdateListener;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserRegisteredPartner::class=>[
           \Btcc\Listeners\EmailNotification::class,
            \Btcc\Listeners\FundingTransaction::class
        ],

       /* 'Btcc\Events\UserRegistration'=>[
          'Btcc\Listeners\EmailNotification',
          'Btcc\Listeners\FundingTransaction'
        ],*/
          ProfileWasUpdated::class=>[
            ProfileUpdateListener::class
        ],


    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        \Btcc\Listeners\UserEventListener::class,
        \Btcc\Listeners\TransactionEventListener::class,
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
