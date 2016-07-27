<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 7/27/2016
 * Time: 4:24 PM
 * Filename: ProfileInfo.php
 */

namespace Btcc\Utilities;

/**
 * Class ProfileInfo
 * @package Btcc\Utilities
 */
class ProfileInfo  {

    public static function getValidationRules() {
        return ['address' =>'string|min:6'];
    }

    public $address;
    public $wallets;
    public $gender;
    public $field1;
    public $field2;
    public $field3;

}