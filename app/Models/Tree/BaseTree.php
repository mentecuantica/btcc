<?php

namespace Btcc\Models\Tree;

use Btcc\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseTree
 * @package Btcc\Models\Tree
 */
abstract class BaseTree implements UserTreeable {

    protected $userId;
    protected $userModel;

    public function __construct(User $currentUser)
    {
        $this->initTreeParent($currentUser);
    }

    /**
     * Relation with User model
     * @return User
     */
    public function getUserModel()
    {
        return $this->userModel;
    }

    public function initTreeParent(User $user)
    {
        $this->userModel = $user;
        $this->userId = $user->id;
    }

}
