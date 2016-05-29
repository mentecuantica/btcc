<?php

namespace Btcc\Listeners;

use Btcc\Events\UserRegisteredPartner;
use Btcc\Models\Transaction;
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

        $user = $event->user;
        $newPartner = $event->newPartner;

        \Log::info('New transaction for partner created',['partner'=>$newPartner]);
        $ut = new Transaction();
        $ut->user_id = \Auth::id();
        $ut->amount = 100;
        $ut->type = Transaction::TYPE_REGISTER_FUNDING;
        $ut->status = 0;
        $ut->sender = $newPartner->getParentId();
        $ut->reciever = $newPartner->id;
        $ut->debit_flag = true;
        if ($ut->save()) {
            logger('New register funding transaction created',compact('ut','newPartner','user'));

        }



    }
}
