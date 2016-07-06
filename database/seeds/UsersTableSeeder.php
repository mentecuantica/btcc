<?php
use Btcc\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

	protected $faker;


	public function run()
	{
		//DB::table('users')->truncate();


		DB::table('users')->delete();
		DB::table('tree_linear')->delete();
		DB::table('tree_binary')->delete();
		DB::table('tree_ternary')->delete();

		$faker = Faker\Factory::create();
		$this->faker = $faker;
		$root = $this->createRootUser($faker);


		//\Auth::user()->create($credentials);

		$this->create3TopUsers($faker, $root);

		//$this->createRandom();

	}

	/**
	 * @param $faker
	 *
	 * @return array
	 */
	protected function createRootUser($faker)
	{
		$initSequence = "SELECT setval('users_id_seq'::regclass,1,false)";
		DB::connection()->getPdo()->exec($initSequence);
		$credentials = [
			'email'    => 'admin@btcc.vgt',
			'password' => bcrypt('123456'),
			'name'     => $faker->firstName,
			'role'     => 'admin',
			'last_name'=>$faker->lastName,
			'first_name'=>$faker->firstName,
		];

		$root = User::create($credentials);

		$root->linear->makeRoot();

		DB::table('tree_ternary')->insert([
			[
				'user_id'           => $root->id,
				'parent_id'         => 0,
				't_position'        => 'M',
				'level_abs'         => 0,
				'l'                 => $root->id,
				'm'                 => $root->id,
				'r'                 => $root->id,
				'position_on_level' => 1,
				'path_to_root'      => '1'
			],

		]);

		DB::table('tree_binary')->insert([
			['parent_id'=>0,'child_id'=>1,'bt_position'=>'N','depth'=>0]]);

		return 	$root;
	}

	/**
	 * @param $faker
	 * @param $root
	 */
	protected function create3TopUsers($faker, $root)
	{
		foreach (range(1, 3) as $number) {
			$credentials = [
				'email'    => $faker->email,
				'password' => bcrypt('123456'),
				'name'     => $faker->firstName,
				//'last_name'=>$faker->lastName,
				'last_name'=>$faker->lastName,
				'first_name'=>$faker->firstName,
			];

			$user = User::create($credentials);
			$user->linear->parent_id = $root->id;
			$user->linear->save();

		}
	}


		/**
	 * @param $faker
	 * @param $root
	 */
	protected function createSubUsers($parent, $count = 2)
	{
		foreach (range(1, $count) as $number) {
			$credentials = [
				'email'    => $this->faker->email,
				'password' => bcrypt('123456'),
				'name'     => $this->faker->firstName,
				//'last_name'=>$faker->lastName,
				'last_name'=>$this->faker->lastName,
				'first_name'=>$this->faker->firstName,
			];

			$user = User::create($credentials);
			$user->linear->parent_id = $parent->id;
			$user->linear->save();

		}
	}

	protected function createRandom()
	{
		$users = User::where(['role' => 'user'])->get()->random(1);
		/**@var $users \Illuminate\Database\Eloquent\Collection * */

		$users->each(function ($someUser) {
			$this->createSubUsers($someUser);
		});
	}

}
