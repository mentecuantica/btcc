<?php

namespace Btcc\Listeners;

use Btcc\Events\UserRegisteredPartner;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FundingTransaction
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     
     * 
*@param  UserRegisteredPartner $event
     * 
*@return void
     */
    public function handle(UserRegisteredPartner $event)
    {
        \Log::info('New transaction for partner created',['partner'=>$event->newPartner]);
        Debugbar::info('New transaction for partner created',['partner'=>$event->newPartner]);
    }
}
