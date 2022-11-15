<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'dashboard', 'guard_name' => 'web']);
        Permission::create(['name' => 'pelanggan', 'guard_name' => 'web']);
        Permission::create(['name' => 'karyawan', 'guard_name' => 'web']);
        Permission::create(['name' => 'paket', 'guard_name' => 'web']);
        Permission::create(['name' => 'transaksi', 'guard_name' => 'web']);
        Permission::create(['name' => 'laporan', 'guard_name' => 'web']);
        Permission::create(['name' => 'tugas-saya', 'guard_name' => 'web']);
        Permission::create(['name' => 'jenis-referensi', 'guard_name' => 'web']);
        Permission::create(['name' => 'referensi', 'guard_name' => 'web']);
        Permission::create(['name' => 'no-rekening', 'guard_name' => 'web']);
        Permission::create(['name' => 'roles', 'guard_name' => 'web']);
        Permission::create(['name' => 'user', 'guard_name' => 'web']);
        Permission::create(['name' => 'dashboard-tugas-saya','guard_name'=>'web']);
        //assign to role admin
        $permissions = Permission::all();
        $role = \Spatie\Permission\Models\Role::findByName('admin');
        $role->syncPermissions($permissions);


    }
}
