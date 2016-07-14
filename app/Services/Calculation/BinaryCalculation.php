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
 * Class NullCalculation
 * @package Btcc\Services
 */
class BinaryCalculation extends Calculation {

    /**
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