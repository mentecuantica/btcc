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
class NullCalculation extends BaseCalculation {

    public function calculate()
    {
        return 0;
    }

}