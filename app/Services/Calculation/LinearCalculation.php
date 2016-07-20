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
 * Class LinearCalculation
 * @package Btcc\Services
 */
class LinearCalculation extends BaseCalculation {



    protected static $planByLevelPercentage = [ 1=>15, 2=>2.5, 3=>1.25 ];

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