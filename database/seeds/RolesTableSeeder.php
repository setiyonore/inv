<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\User;
class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        CREATE ROLES
        Role::create(['name'=>'admin']);
//        ASSIGN ROLES TO USERS
        $role = Role::query()->find(1);
        $user = User::query()->find(1);
        $user->assignRole($role);


    }
}
