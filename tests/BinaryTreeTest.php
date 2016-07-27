<?php

use \Btcc\Models\Tree\TreeBinary;
use Btcc\Models\User;

/**
 * Class BinaryTreeTest
 * @package tests
 */
class BinaryTreeTest extends TestCase{


    
    public function test_is_top_user()
    {
        $user = $this->loadTopUser();
        $this->actingAs($user);

        $result = $user->binary->isTopUser();


        $this->assertTrue($result);



    }


    public function test_has_partners()
    {
        $user = $this->loadTopUser();
        $this->actingAs($user);

        $result = $user->binary->hasPartners();


        $this->assertTrue($result);



    }

    public function test_something_binary()
    {
        $user = $this->loadTopUser();


    }
}