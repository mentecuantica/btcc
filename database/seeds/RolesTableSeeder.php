<?php

use Illuminate\Database\Seeder;



class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $admin = [
            'email'    => 'admin@admin.com',
            'password' => 'adminadmin',
        ];


        $adminUser = Sentinel::registerAndActivate($admin);
        $role = [
            'name' => 'Super Administrator',
            'slug' => 'saadmin',
            'permissions' => [
                'saadmin' => true,
            ]
        ];
        $adminRole = Sentinel::getRoleRepository()->createModel()->fill($role)->save();
        $adminUser->roles()->attach($adminRole);


        $role = [
            'name' => 'operator',
            'slug' => 'op',
        ];
        $userRole = Sentinel::getRoleRepository()->createModel()->fill($role)->save();


        $role = [
            'name' => 'user',
            'slug' => 'user',
        ];
        $userRole = Sentinel::getRoleRepository()->createModel()->fill($role)->save();

        $role = [
            'name' => 'Banned',
            'slug' => 'banned',
        ];
        $banRole = Sentinel::getRoleRepository()->createModel()->fill($role)->save();
    }
}
