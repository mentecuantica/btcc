<?php
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {



	public function run()
	{
		//DB::table('users')->truncate();
		DB::table('roles')->truncate();
		DB::table('tree_linear')->truncate();
		DB::table('role_users')->truncate();

		DB::table('users')->delete();

		$faker = Faker\Factory::create();

		$initSequence = "SELECT setval('users_id_seq'::regclass,1,false)";
		DB::connection()->getPdo()->exec($initSequence);
		$credentials = [
			'email'    => 'top@btcc.vgt',
			'password' => '123456',
			'first_name'=> $faker->firstName,
			'last_name'=>$faker->lastName,
		];

		$superUser = Sentinel::registerAndActivate($credentials);


		$role = [
			'name' => 'TopUser',
			'slug' => 'topUser',
			'permissions' => [
				'allTree' => true,
			]
		];
		$topUserRole = Sentinel::getRoleRepository()->createModel()->fill($role)->save();
		$superUser->roles()->attach($topUserRole);


		foreach (range(1, 10) as $number) {
			$credentials = [
				'email'    => $faker->email,
				'password' => '123456',
				'first_name'=> $faker->firstName,
				'last_name'=>$faker->lastName,
			];

			$user = Sentinel::registerAndActivate($credentials);
		}
		//dd($user);

		//Sentinel::activate($user);



	}

	public function run1()
	{


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
