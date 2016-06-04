<?php
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {



	public function run()
	{
		$faker = Faker\Factory::create();

		for($i=0;$i<40;$i++){

			$credentials = [
				'email'    => $faker->email,
				'password' => 'demo123',
			];

			Sentinel::registerAndActivate($credentials);

		}

	}

	public function run1()
	{
		/*DB::table('users')->truncate();
		DB::table('roles')->truncate();
		DB::table('role_users')->truncate();*/

		$role = [
			'name' => 'Administrator',
			'slug' => 'administrator',
			'permissions' => [
				'admin' => true,
			]
		];

		$adminRole = Sentinel::getRoleRepository()->createModel()->fill($role)->save();

		$subscribersRole = [
			'name' => 'Subscribers',
			'slug' => 'subscribers',
		];

		Sentinel::getRoleRepository()->createModel()->fill($subscribersRole)->save();

		$admin = [
			'email'    => 'admin@example.com',
			'password' => 'password',
		];

		$users = [

			[
				'email'    => 'demo1@example.com',
				'password' => 'demo123',
			],

			[
				'email'    => 'demo2@example.com',
				'password' => 'demo123',
			],

			[
				'email'    => 'demo3@example.com',
				'password' => 'demo123',
			],

		];

		$adminUser = Sentinel::registerAndActivate($admin);
		$adminUser->roles()->attach($adminRole);

		foreach ($users as $user)
		{
			Sentinel::registerAndActivate($user);
		}
	}

}
