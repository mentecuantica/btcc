<?php

use Illuminate\Database\Seeder;


class PackageTableSeeder extends Seeder
{
    public function run()
    {
        $initSequence = "SELECT setval('packages_id_seq'::regclass,1,false)";
        //DB::connection()->getPdo()->exec($initSequence);
        //$pdoStatement = DB::connection()->getPdo()->prepare('SELECT * FROM bt_validate_position_json(:parent_id, :new_pos)');

       //$query =  DB::connection()->query()->selectRaw('* FROM bt_validate_position_json(:pid, :pos)',[3,'R']);

        $pdoStatement->execute([':parent_id'=>3,':new_pos'=>'R']);

        DB::table('packages')->delete();

        factory(\Btcc\Models\Package::class, 3)->create();
       //  TestDummy::times(20)->create('App\Post');
    }
}
