<?php

namespace Btcc\Policies;

use Btcc\Models\User;
use Btcc\Services\PackageService;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class UserPolicy
{
    use HandlesAuthorization;

    protected $request;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        \Debugbar::addMessage('Policy construct ','error');

    }

    public function addPartner(User $user)
    {
        /**
         * @var  PackageService $packageService
         */
        $packageService = app(PackageService::class);

        $userFinances = $this->request->user()->totalSum;

        $minimumPackageCost = 100;

        $canAdd = $userFinances >= $minimumPackageCost;
        \Debugbar::addMessage('Can add partner '.$canAdd,'error');


        return $canAdd;
    }
}
