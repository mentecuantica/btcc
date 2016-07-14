<?php

use Illuminate\Database\Seeder;


class TreeBinaryTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('tree_binary')->delete();

        DB::table('tree_binary')->insert([
            ['user_id'=>1,'parent_id'=>0,'bt_position'=>'N','depth'=>0],
            ['user_id'=>2,'parent_id'=>1,'bt_position'=>'L','depth'=>1],
            ['user_id'=>3,'parent_id'=>1,'bt_position'=>'R','depth'=>1],
            ['user_id'=>4,'parent_id'=>2,'bt_position'=>'L','depth'=>2],

           /* ['parent_id'=>2,'user_id'=>5,'bt_position'=>'R','depth'=>2],
            ['parent_id'=>3,'user_id'=>6,'bt_position'=>'R','depth'=>2],
            ['parent_id'=>4,'user_id'=>7,'bt_position'=>'L','depth'=>3],
            ['parent_id'=>5,'user_id'=>8,'bt_position'=>'L','depth'=>3],
            ['parent_id'=>5,'user_id'=>9,'bt_position'=>'R','depth'=>3],
            ['parent_id'=>3,'user_id'=>10,'bt_position'=>'L','depth'=>2],*/
        ]);
    }
}
