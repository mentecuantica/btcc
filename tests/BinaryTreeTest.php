<?php

use \Btcc\Models\Tree\TreeBinary;
/**
 * Class BinaryTreeTest
 * @package tests
 */
class BinaryTreeTest extends TestCase{

    public function testGetUserNestedArrayModels()
    {
        $parentUser = 1;
        $usersCollection = TreeBinary::getUserDescendantsModels($parentUser);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class,$usersCollection);

        TreeBinary::buildNestedUserArray($usersCollection,$parentUser);
    }
}