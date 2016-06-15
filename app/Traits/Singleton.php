<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 6/15/2016
 * Time: 12:17 AM
 * Filename: Singleton.php
 */

namespace Btcc\Traits;

trait Singleton {
    /**
     * Store the singleton object.
     */
    private static $singleton = false;

    /**
     * Create an inaccessible contructor.
     */
    private function __construct() {
        $this->__instance();
    }

    /**
     * Fetch an instance of the class.
     */
    public static function getInstance() {
        if (self::$singleton === false) {
            self::$singleton = new self();
        }

        return self::$singleton;
    }
}