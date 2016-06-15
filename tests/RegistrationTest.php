<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
use Btcc\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationTest extends TestCase
{

    public function testPartnerPage()
    {
        $credentials = [
            'email'    => 'top@btcc.vgt',
            'password' => '123456',
        ];

        $user = User::find(1);

        \Sentinel::setUser($user);

        $this->actingAs($user)->visit('/')->click('View partners')
            ->see('Binary partners count');
    }


    public function testAddPartnerPage()
    {
        $credentials = [
            'email'    => 'top@btcc.vgt',
            'password' => '123456',
        ];

        $user = User::find(1);

        \Sentinel::setUser($user);

        $this->actingAs($user)->visit('/partner')->click('Add partner')
            ->see('Add partner');
    }


    public function testAddPartnerFunctional()
    {
        $credentials = [
            'email'    => 'top@btcc.vgt',
            'password' => '123456',
        ];

        $user = User::find(1);

        \Sentinel::setUser($user);

        $this->actingAs($user)->visit('/partner/create')
            ->type('Valentindas 1','first_name')
            ->type('Popopdasop 2','last_name')
            ->type('adasdaghey@bitch.ru','email')
            ->select('1','package_id')
            ->select('1','country_id')
            ->select('R','binary-position')
            ->type('9','binary-parent-id')
            ->check('user_agreement')
            ->press('Add partner')
            ->see('The email has already been taken');
    }

    public function testNewUserLogin()
    {
        $email = 'adasdaghey@bitch.ru';

        $this->visit('/login')
            ->type($email,'email')
            ->type('icLGie6a','password')
            ->press('Login')
            ->see($email);

    }
}