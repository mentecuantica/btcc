<?php

namespace Btcc\Events;

use Btcc\Events\Event;
use Btcc\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserRegisteredPartner extends Event
{
    use SerializesModels;

    public $user;
    public $newPartner;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, User $newPartner)
    {
        $this->user = $user;
        $this->newPartner = $newPartner;

     //   \Debugbar::addMessage('UserRegisteredPartner event fired ');
        \Log::info('UserRegisteredPartner event fired: ',compact('user','newPartner'));
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
