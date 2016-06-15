<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 6/15/2016
 * Time: 1:38 AM
 * Filename: Linear.php
 */

namespace Btcc\Models\Temp;
use Btcc\Models\Temp;

/**
 * Class Linear
 * @package Btcc\Models\Temp
 */
class Linear extends BaseTree {
    /**
     * Relation with User model
     * @return User
     */
    public function getUserModel()
    {
        // TODO: Implement getUserModel() method.
    }

    /**
     * Get a Parent user
     * in all treeable structures - there is a parent
     * @return User
     */
    public function getParentUser()
    {
        // TODO: Implement getParentUser() method.
    }

    /**
     * ID ?
     * @return mixed
     */
    public function getParent()
    {
        // TODO: Implement getParent() method.
    }

    /**
     * @return bool
     */
    public function isRoot()
    {
        // TODO: Implement isRoot() method.
    }

    /**
     * @return bool
     */
    public function hasDescendants()
    {
        // TODO: Implement hasDescendants() method.
    }

    public function countDescendants()
    {
        // TODO: Implement countDescendants() method.
    }

    public function countChildren()
    {
        // TODO: Implement countChildren() method.
    }

    public function getAllDescendants()
    {
        // TODO: Implement getAllDescendants() method.
    }

    /**
     * Returns those who on level $depthLimit below this user
     * @return mixed
     */
    public function getDescendants($depthLimit = 5)
    {
        // TODO: Implement getDescendants() method.
    }

    /**
     * Returns those who on level 1 below this user
     * @return mixed
     */
    public function getChildren()
    {
        // TODO: Implement getChildren() method.
    }

}