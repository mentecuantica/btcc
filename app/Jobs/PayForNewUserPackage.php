<?php

namespace Btcc\Jobs;

use Btcc\Models\Transaction\BaseTransaction;
use Btcc\Models\Transaction\Transactable;
use Btcc\Models\Transaction\UserTransaction;
use Btcc\Models\User;
use Btcc\Services\PackageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PayForNewUserPackage extends Job implements ShouldQueue {
    use InteractsWithQueue, SerializesModels;

    protected $packageService;
    protected $newUser;

    /**
     * Create a new job instance.
     *
     * @param \Btcc\Models\User $newUser
     */
    public function __construct(User $newUser)
    {
        $this->newUser = $newUser;
    }

    /**
     * @return \Btcc\Services\PackageService
     */
    public function getPackageService(): PackageService
    {
        return $this->packageService;
    }

    /**
     * @param \Btcc\Services\PackageService $packageService
     *
     * @return PayForNewUserPackage
     */
    public function setPackageService(PackageService $packageService): PayForNewUserPackage
    {
        $this->packageService = $packageService;

        return $this;
    }

    /**
     * Execute the job.
     *
     * @param \Btcc\Services\PackageService $packageService
     */
    public function handle(PackageService $packageService)
    {

        $withdrawCost = user()->getPackageAttribute()->price;
        $availableFunds = user()->getTotalSumAttribute();

        $t = $this->createWithdrawTransaction($withdrawCost, $availableFunds);

        user()->transactions()->save($t);

        \Log::critical('Your balance now:  ', [
            user(),
            $t,
            $this->newUser
        ]);

    }

    /**
     * @param int $amount
     * @param int $availableFunds
     *
     * @return \Btcc\Models\Transaction\UserTransaction
     */
    protected function createWithdrawTransaction(int $amount, int $availableFunds)
    {

        $attributes = [
            'amount'      => $amount,
            'type'        => Transactable::TYPE_REGISTER_WITHDRAW,
            'debit_flag'  => FALSE,
            'credit_flag' => TRUE,

        ];

        $rules = [
            'amount' => 'required|numeric|max:' . $availableFunds,
            'credit_flag' => 'required|boolean|same:false',
            'debet_flag'  => 'required|boolean|same:true',
        ];

        $v = \Validator::make($attributes, $rules);
        if ($v->fails()) {

            \Log::critical('Transaction validator in Job failed', [$v]);

            $this->failed();
        }

        $t = new UserTransaction($attributes);
        $t->user_id = user()->id;
        $t->sender_id = user()->id;
        $t->reciever_id = $this->newUser->id;
        $t->status = Transactable::STATUS_NEW;

        return $t;
    }
}
