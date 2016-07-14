<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 7/14/2016
 * Time: 3:15 AM
 * Filename: Calculatable.php
 */

namespace Btcc\Services\Calculation\Contracts;

interface Calculatable {

    function prepareData();


    function calculate();
}