<?php

use Illuminate\Database\Seeder;


class PackageTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('packages')->delete();

        factory(\Btcc\Models\Package::class, 5)->create();
       //  TestDummy::times(20)->create('App\Post');
    }
}
