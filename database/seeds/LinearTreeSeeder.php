<?php

use Illuminate\Database\Seeder;
use Btcc\Models\User;
use Btcc\Models\Tree\TreeLinear;


class LinearTreeSeeder extends Seeder {
    /**
     * Run the database seeds.
     * @return void
     */

    public function run()
    {
        $userIds = TreeLinear::get(['user_id','parent_id','lft'])->pluck('user_id');

        $shuffled = $userIds->shuffle();


        $root = TreeLinear::where(['user_id'=>1])->first();

        $root->makeRoot();

        for($i=1;$i<8;$i++) {

            $randomId = $shuffled->pop();

            if ($randomId!=$root->user_id) {

                $link = TreeLinear::where(['user_id'=>$randomId])->first();

                $link->makeChildOf($root);
            }
        }

        $more = TreeLinear::all()->random(5);


       // dd($root);
    }
    
    
    public function run2()
    {
        $faker = Faker\Factory::create();

        /**
         *
         *  0. Choose User -> make him root user
         * 
         *  1. Choose random user from User's
         *
         *
         */
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
