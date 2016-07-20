<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 7/14/2016
 * Time: 3:09 AM
 * Filename: NullCalculation.php
 */

namespace Btcc\Services\Calculation;

/**
 * Class TernaryCalculation
 * @package Btcc\Services
 */
class TernaryCalculation extends BaseCalculation {

    /**
     *
     * Seems to be fixed $10
     *
     * From top to bottom, from L->R ???
     *
     *
     * Get User -> and his direct children
     * Get their packages
     *      Get Limits and Bonuses from PackagePlan
     *
     */
    public function prepareData()
    {
    }



    public function calculate()
    {
        return 0;
    }

}