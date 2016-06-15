<?php

namespace Btcc\Models\Temp;

use Illuminate\Database\Eloquent\Model;

/**
 * Каждый хэлпер дерева должен содержать reference -> userId, userModel
 *
 *
 * Class BaseTree
 * @package Btcc\Models\Temp
 */
abstract class BaseTree implements UserTreeableInterface {

    protected $userId;
    protected $user;

    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     * param [ mixed $args [, $... ]]
     *
     * @param \Btcc\Models\Temp\User $userModel
     *
     * @link http://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct(User $userModel)
    {
        $this->user = $userModel;
        $this->userId = $userModel->getUserId();
    }

    /**
     * Relation with User model
     * @return User
     */
    public function getUserModel()
    {
        return $this->user;
    }

}
