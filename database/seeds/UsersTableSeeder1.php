<?php

use Illuminate\Database\Seeder;
use Btcc\Models\User;

class UsersTableSeeder1 extends Seeder {
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        //factory(User::class,10)->create();

        User::unguard();

        $root = User::root();
        if (User::roots()->count() == 0) {

            $rootUser = [
                'name'            => 'Super Mlm',
                'email'           => $faker->email,
                'password'        => bcrypt('1235456'),
                'binary_position' => 'U'

            ];
            $root = User::create($rootUser)->makeRoot();
        }


        $nestedSeed = function(int $count =10,User $parentUser) use ($faker) {
            for ($i = 0; $i < $count; $i++) {

                $userRandom = User::create([
                    'name'            => $faker->name,
                    'email'           => $faker->email,
                    'password'        => bcrypt('1235456'),
                    'binary_position' => $faker->randomElement([
                        'L',
                        'R'
                    ])
                ]);

                $userRandom->makeChildOf($parentUser);
            }

        };

        $nestedSeed(10,$root);


        $usersRandom = User::all()->random(5);
        foreach ($usersRandom as $user) {
            /**@var User $user **/
            if ($user->isRoot()) {
                continue;
            }
            $nestedSeed(10,$user);
        }




        User::reguard();
        if (DB::connection()->getDriverName() === 'pgsql') {
            // $tablePrefix = DB::connection()->getTablePrefix();
            $sequenceName
                = 'users_id_seq';
            DB::connection()->statement('ALTER SEQUENCE ' . $sequenceName . ' RESTART WITH 7');
        }
    }

    public function nestUptoAt($node, $levels = 10, $attrs = [])
    {
        for ($i = 0; $i < $levels; $i++, $node = $new) {
            $new = User::create(array_merge($attrs, ['name' => "{$node->name}.1"]));
            $new->makeChildOf($node);
        }
    }

}
