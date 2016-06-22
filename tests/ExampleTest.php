<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
use Btcc\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{

    public function testApplication()
    {
        $response = $this->call('GET', '/');

        $this->assertEquals(200, $response->status());
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit('/')
             ->see('Login');
    }

    public function testGoToForgotPassword()
    {
        $this->visit('/')->click('Forgot Your Password?')->assertResponseOk();
    }


    public function testWithUser()
    {
        $credentials = [
            'email'    => 'top@btcc.vgt',
            'password' => '123456',
        ];



        $user = User::find(1);

        \Sentinel::setUser($user);

        $this->actingAs($user)->visit('/')
            ->see($user->getUserLogin())->see('Logout');
    }


}
