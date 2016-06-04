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

    /**
     * @return User
     */
    public function user();


    /**
     * @return bool
     */
    public function isTopUser();

    /**
     * @return bool
     */
    public function hasPartners();




    public function allPartners();


    /**
     * Returns those who on level $depthLimit below this user
     *
     * @return mixed
     */
    public function subPartners($depthLimit =5 );


    /**
     * Returns those who on level 1 below this user
     *
     * @return mixed
     */
    public function directPartners();


}