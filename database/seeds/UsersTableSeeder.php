<?php
use Btcc\Models\User;
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
			'password' => bcrypt('123456'),
			'name'=> $faker->firstName,
			//'last_name'=>$faker->lastName,
		];


		User::create($credentials);

		//\Auth::user()->create($credentials);


		foreach (range(1, 10) as $number) {
			$credentials = [
				'email'    => $faker->email,
				'password' => bcrypt('123456'),
				'name'=> $faker->firstName,
				//'last_name'=>$faker->lastName,
			];

			User::create($credentials);

		}



	}



}
