<?php
/**
 * Author: Yuri Efimov
 * User: octopus
 * Date: 5/27/2016
 * Time: 6:41 AM
 * Filename: UserHierarchy.php
 */

namespace Btcc\Traits;

trait LinearTreeable  {

    /**
     * @return bool
     */
    public function isTopUser()
    {
        return $this->linear()->isRoot();
    }

    /**
     * @return bool
     */
    public function hasPartners()
    {
        return !$this->linear()->isLeaf();
    }


    public function allPartners()
    {
        return $this->linear()->descendants();
    }


    /**
     * Returns those who on level $depthLimit below this user
     *
     * @return mixed
     */
    public function subPartners($depthLimit =5 )
    {
        return $this->linear()->descendants()->limitDepth($depthLimit)->get();
    }



    /**
     * Returns those who on level 1 below this user
     *
     * @return mixed
     */
    public function directPartners()
    {
        return $this->linear()->children()->get();
    }
}