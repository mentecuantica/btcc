<?php
/**
 * Author: Yuri Efimov
 * User: octopus
 * Date: 5/29/2016
 * Time: 7:05 AM
 * Filename: TransactionCreated.php
 */

namespace Btcc\Events\Transaction;
use Btcc\Events\Event;
use Btcc\Models\Transaction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
/**
 * Class TransactionCreated
 * @package Btcc\Events\Transaction
 */
class TransactionChangeStatus extends Event {

    use SerializesModels;

    public $transaction;
    public $transactionId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
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