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
            'email'    => 'admin@btcc.vgt',
            'password' => '123456',
        ];

        $user = User::where(['email'=>$credentials['email']])->first();

        \Auth::setUser($user);

        $this->actingAs($user)->visit('/')->click('View partners')
            ->see('Binary partners count');
    }

    public function testShowPage()
    {
        $credentials = [
            'email'    => 'emclaughlin@yahoo.com',
            'password' => '123456',
        ];

        $user = User::all()->random();

        \Auth::setUser($user);

        $this->actingAs($user)->visit('/partner')->click('Add partner')->visit('/tree/show/4');

    }

    public function testAddPartnerPage()
    {
        $credentials = [
            'email'    => 'admin@btcc.vgt',
            'password' => '123456',
        ];

        $user = User::where(['email'=>$credentials['email']])->first();

        \Auth::setUser($user);

        $this->actingAs($user)->visit('/partner')->click('Add partner')
            ->see('Add partner');
    }


    public function testAddPartnerMailTaken()
    {
        $credentials = [
            'email'    => 'admin@btcc.vgt',
            'password' => '123456',
        ];

        $user = User::where(['email'=>$credentials['email']])->first();

        \Auth::setUser($user);


        $package =\Btcc\Models\Package::all()->random(1);

        $this->actingAs($user)->visit('/partner/create')
            ->type('Valentindas 1','first_name')
            ->type('Popopdasop 2','last_name')
            ->type('adasdaghey@bitch.ru','email')
            ->select($package->id,'package_id')
            ->select('au','country_code')
            ->select('R','binary-position')
            ->type('9','binary-parent-id')
            ->check('user_agreement')
            ->press('Add partner')
            ->see('The email has already been taken');
    }


    public function testAddPartnerWrongBinary()
    {
        $credentials = [
            'email'    => 'admin@btcc.vgt',
            'password' => '123456',
        ];

        $user = User::where(['email'=>$credentials['email']])->first();

        \Auth::setUser($user);

        $faker = Faker\Factory::create();


        $package =\Btcc\Models\Package::all()->random(1);

        $this->actingAs($user)->visit('/partner/create')
            ->type($faker->firstName,'first_name')
            ->type($faker->lastName,'last_name')
            ->type($faker->email,'email')
            ->select($package->id,'package_id')
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

    public static function testCreateTreeUserBundle()
    {
        $email = 'test@repo.ru';

        $oldUser = \Btcc\Models\User::with('linear')->where('email', '=', $email)->first();

        if ($oldUser) {
            /**@var User $oldUser * */
            $oldUser->linear->delete();
            $oldUser->profile->delete();
            $oldUser->delete();
        }

        $user = [
            'email'    => 'test@repo.ru',
            'password' => '123456'
        ];
        $package_id = \Btcc\Models\Package::all('id')->random()->id;

        $profile = [
            'phone'      => '9222222222',
            'package_id' => $package_id,
        ];

        $binary = [
            'parent_id' => 4,
            'position'  => 'R'
        ];

        static::addNewUser(1, $user, $profile, $binary);
    }
}
