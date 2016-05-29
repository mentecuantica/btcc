<?php

namespace Btcc\Listeners;

use Btcc\Events\UserRegisteredPartner;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailNotification
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
       $user = $event->user;
       $newPartner = $event->newPartner;


        \Debugbar::addMessage('Email to new partner goes: '.$newPartner->email);


        

    }
}
