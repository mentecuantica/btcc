<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 6/4/2016
 * Time: 4:43 AM
 * Filename: UserTreeable.php
 */

namespace Btcc\Models\Tree;

use Btcc\Models\User;

interface UserTreeable {

    public function initTreeParent(User $user);

    /**
     * @return User
     */
    public function getParentUser();


    public function getParents();

    /**
     * @return bool
     */
    public function isTopUser();

    /**
     * @return bool
     */
    public function hasPartners();




    public function getPartners();


    /**
     * Returns those who on level $depthLimit below this user
     *
     * @return mixed
     */
    public function getPartnersLimit($depthLimit =5 );


    /**
     * Returns those who on level 1 below this user
     *
     * @return mixed
     */
    public function getFirstPartners();

    public function countPartners();

    public function countFirstPartners();

}