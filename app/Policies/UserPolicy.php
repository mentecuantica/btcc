<?php

namespace Btcc\Policies;

use Btcc\Models\User;
use Btcc\Services\PackageService;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function addPartner(User $user)
    {
        /**
         * @var  PackageService $packageService
         */
        $packageService = app(PackageService::class);

        $userFinances = $user->totalSum;

        $minimumPackageCost = 100;



        return  $userFinances >= $minimumPackageCost;
    }
}
