<?php
use Btcc\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

    protected $faker;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $packageIds;

    public function run()
    {
        $this->packageIds = app(\Btcc\Services\Subscriptions\SubscribeForPackage::class)->getPackagesIds();

        //dd($this->packageIds);
        //DB::table('users')->truncate();

        DB::table('users')->delete();
        DB::table('profiles')->delete();
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

        factory(Btcc\Models\User::class, 1)->create([
            'email' => 'admin@btcc.vgt',
            'role'  => 'admin'
        ])->profile()->save(factory(Btcc\Models\Profile::class)->make());
        $root = User::find(1);
        $root->linear->makeRoot();

        $id = $root->id;
        DB::table('tree_ternary')->insert([
            [
                'user_id'           => $id,
                'parent_id'         => 0,
                't_position'        => 'M',
                'level_abs'         => 0,
                'l'                 => $id,
                'm'                 => $id,
                'r'                 => $id,
                'position_on_level' => 1,
                'path_to_root'      => '1'
            ],

        ]);

        DB::table('tree_binary')->insert([
            [
                'parent_id'   => 0,
                'user_id'     => $id,
                'bt_position' => 'N',
                'depth'       => 0
            ]
        ]);

        return $root;
    }

    /**
     * @param Faker\Factory $faker
     * @param $root
     */
    protected function create3TopUsers($faker, $root)
    {
        //$packageIds = \Btcc\Models\Package::all('id');
        $packageIds = [
            'SRT1',
            'ADV2',
            'PRF3'
        ];

        //Btcc\Models\Package::all('id');

        $users = factory(Btcc\Models\User::class, 3)->create(

        //    [ 'package_id' => $this->packageIds->random(),]
        //    [ 'package_id' =>$faker-> ]

        )->each(function ($user) use ($root) {
            $user->profile()->save(factory(Btcc\Models\Profile::class)->make());
            $user->linear->parent_id = $root->id;
            $user->linear->save();
        });

        /*
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

                }*/
    }

    /**
     * @param $faker
     * @param $root
     */
    protected function createSubUsers($parent, $count = 2)
    {
        foreach (range(1, $count) as $number) {
            $credentials = [
                'email'      => $this->faker->email,
                'password'   => bcrypt('123456'),
                'name'       => $this->faker->firstName,
                //'last_name'=>$faker->lastName,
                'last_name'  => $this->faker->lastName,
                'first_name' => $this->faker->firstName,
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
