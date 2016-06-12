<?php

namespace Btcc\Events;

use Btcc\Events\Event;
use Btcc\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserRegisteredPartner extends Event
{
    use SerializesModels;


    public $newPartner;
    public $passwordPlain;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $newPartner, string $passwordPlain)
    {
        $this->passwordPlain = $passwordPlain;
        $this->newPartner = $newPartner;

     //   \Debugbar::addMessage('UserRegisteredPartner event fired ');
        \Log::info('UserRegisteredPartner event fired: ',compact('newPartner'));
       // \Debugbar::info('UserRegisteredPartner event called: ',compact('user','newPartner'));
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
