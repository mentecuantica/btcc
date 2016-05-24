<?php

namespace Btcc\Events;

use Btcc\Events\Event;
use Btcc\Models\Profile;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProfileWasUpdated extends Event
{
    use SerializesModels;

    public $profile;
    public $isNew;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Profile $profile, $isNew = FALSE)
    {
        $this->profile = $profile;
        $this->isNew = $isNew;
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
