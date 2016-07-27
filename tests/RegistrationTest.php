<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
use Btcc\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationTest extends TestCase
{

    public function testPartnerPage()
    {
        $user = $this->loadTopUser();

        $this->actingAs($user)->visit('/')->click('View')
            ->see('Partners count');
    }

    public function testShowPage()
    {



    }

    public function testAddPartnerPage()
    {
        $this->actingAs($this->loadTopUser())->visit('/partner')
            ->click('Add partner')
            ->see('User agreement')
            ->see('Add partner');
    }


    public function testAddPartnerMailTaken()
    {

        $user = $this->loadTopUser();

        $packages = ['SRT1','ADV2','PRF3'];
        


        $this->actingAs($user)->visit('/partner/create')
            ->type('Valentindas 1','first_name')
            ->type('Popopdasop 2','last_name')
            ->type($user->email,'email')
            ->select($packages[0],'package_id')
            ->select('au','country_code')
            ->select('R','binary-position')
            ->type('9','binary-parent-id')
            ->check('user_agreement')
            ->press('Add partner')
            ->see('The email has already been taken');
    }


    public function testAddPartnerWrongBinary()
    {
       $user = $this->loadTopUser();

        $faker = Faker\Factory::create();


        $packages = ['SRT1','ADV2','PRF3'];

        $this->actingAs($user)->visit('/partner/create')
            ->type($faker->firstName,'first_name')
            ->type($faker->lastName,'last_name')
            ->type($faker->email,'email')
            ->select($packages[1],'package_id')
            ->select('au','country_code')
            ->select('R','binary-position')
            ->type('9','binary-parent-id')
            ->check('user_agreement')
            ->press('Add partner');

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
