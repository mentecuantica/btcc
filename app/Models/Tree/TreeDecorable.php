<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 6/15/2016
 * Time: 3:58 AM
 * Filename: TreeDecorable.php
 */

namespace Btcc\Models\Tree;

/**
 * Every tree has own decorator object
 *
 *
 * Interface TreeDecorable
 * @package Btcc\Models\Tree
 */
interface TreeDecorable {

    public function getNestedArray();
    public function getNestedJson();
    public function getNestedModels();
    public function getPlainArray();
    public function getPlainJson();
    public function getPlainModels();

}