<?php

use Illuminate\Database\Seeder;


class PackageTableSeeder extends Seeder
{
    public function run()
    {
        $initSequence = "SELECT setval('packages_id_seq'::regclass,1,false)";
        DB::connection()->getPdo()->exec($initSequence);
        DB::table('packages')->delete();

        factory(\Btcc\Models\Package::class, 3)->create();
       //  TestDummy::times(20)->create('App\Post');
    }
}
