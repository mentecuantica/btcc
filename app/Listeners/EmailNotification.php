<?php

namespace Btcc\Listeners;

use Btcc\Events\UserRegisteredPartner;
use Btcc\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailNotification {
    /**
     * Create the event listener.
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegisteredPartner $event
     *
     * @return void
     */
    public function handle(UserRegisteredPartner $event)
    {
        //$user = $event->user;

        /**@var $user User * */
        $user = $event->newPartner;

        $data = ['login'    => $user->email,
                 'password' => $event->passwordPlain
        ];
        \Mail::send('emails.invite', $data, function ($message) use ($user) {
            $message->from('no-reply@btcc.vgt', 'Btcc');

            $message->to($user->email);
        });

        \Log::info('Email to new partner goes: ' . $user->email);
        \Debugbar::addMessage('Email to new partner goes: ' . $user->email);

    }
}
