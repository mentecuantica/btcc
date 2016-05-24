<?php

namespace Btcc\Listeners;

use Btcc\Events\ProfileWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProfileUpdateListener
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
     * @param  ProfileWasUpdated  $event
     * @return void
     */
    public function handle(ProfileWasUpdated $event)
    {
        if ($event->isNew) {
            flash('New profiles');
        }
        else {
            flash('Update profiles');
        }
    }
}
