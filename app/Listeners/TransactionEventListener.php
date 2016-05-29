<?php

namespace Btcc\Listeners;
use Btcc\Events\Transaction\TransactionChangeStatus;
use Btcc\Events\Transaction\TransactionCreated;

class TransactionEventListener
{
    /**
     * Handle user login events.
     */
    public function onTransactionCreate(TransactionCreated $event) {
        \Debugbar::addMessage('We handlling transaction create');
    }

    /**
     * Handle user logout events.
     */
    public function onTransactionChangeStatus(TransactionChangeStatus $event) {
        \Debugbar::addMessage('We handlling transaction change status');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            TransactionCreated::class,
            '\Btcc\Listeners\TransactionEventListener::onTransactionCreate@on'
        );

        $events->listen(
            TransactionChangeStatus::class,
            '\Btcc\Listeners\TransactionEventListener::onTransactionChangeStatus'
        );
    }

}