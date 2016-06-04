<?php
/**
 * Author: Yuri Efimov
 * User: octopus
 * Date: 5/27/2016
 * Time: 6:41 AM
 * Filename: UserHierarchy.php
 */

namespace Btcc\Traits;

trait BinaryTreeable  {

    /**
     * @return bool
     */
    public function isTopUser()
    {
    }

    /**
     * @return bool
     */
    public function hasPartners()
    {
    }


    public function allPartners()
    {
    }


    /**
     * Returns those who on level $depthLimit below this user
     *
     * @return mixed
     */
    public function subPartners($depthLimit =5 )
    {
    }



    /**
     * Returns those who on level 1 below this user
     *
     * @return mixed
     */
    public function directPartners()
    {
    }
}