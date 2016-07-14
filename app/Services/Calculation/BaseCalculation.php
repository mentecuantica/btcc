<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 7/14/2016
 * Time: 3:11 AM
 * Filename: Calculation.php
 */

namespace Btcc\Services\Calculation;
use Btcc\Services\Calculation\Contracts\Calculatable;

/**
 * Class Calculation
 * @package Btcc\Services\Calculation
 */
abstract class Calculation implements Calculatable {

    protected $user;

    protected $id;

    protected $package;




    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPackage()
    {
        return $this->package;
    }



    public function calculate()
    {
        // TODO: Implement calculate() method.
    }


    public function prepareData()
    {
        // TODO: Implement prepareData() method.
    }

}