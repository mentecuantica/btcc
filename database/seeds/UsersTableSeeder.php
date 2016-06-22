<?php
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {



	public function run()
	{
		//DB::table('users')->truncate();


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

		//$superUser = Auth::user()->create() ::registerAndActivate($credentials);


		$role = [
			'name' => 'TopUser',
			'slug' => 'topUser',
			'permissions' => [
				'allTree' => true,
			]
		];
		


		foreach (range(1, 10) as $number) {
			$credentials = [
				'email'    => $faker->email,
				'password' => '123456',
				'first_name'=> $faker->firstName,
				'last_name'=>$faker->lastName,
			];

			
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


		$subscribersRole = [
			'name' => 'Subscribers',
			'slug' => 'subscribers',
		];


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

		
	}

}
