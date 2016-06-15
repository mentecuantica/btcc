<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 6/4/2016
 * Time: 4:43 AM
 * Filename: UserTreeable.php
 */

namespace Btcc\Models\Temp;

use Btcc\Models\Temp;

interface UserTreeableInterface {

    /**
     * Relation with User model
     *
     * @return User
     */
    public function getUserModel();


    /**
     * Get a Parent user
     * in all treeable structures - there is a parent
     *
     * @return User
     */
    public function getParentUser();

    /**
     * ID ?
     *
     * @return mixed
     */
    public function getParent();

    /**
     * @return bool
     */
    public function isRoot();

    /**
     *
     *
     * @return bool
     */
    public function hasDescendants();

    public function countDescendants();

    public function countChildren();



    public function getAllDescendants();


    /**
     * Returns those who on level $depthLimit below this user
     *
     * @return mixed
     */
    public function getDescendants($depthLimit =5 );


    /**
     * Returns those who on level 1 below this user
     *
     * @return mixed
     */
    public function getChildren();


}