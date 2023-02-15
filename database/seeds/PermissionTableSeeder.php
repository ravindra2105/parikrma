<?php


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $permissions = [
           'role-list',
           'role-create',
           'role-edit',
           'role-delete',
           'project-list',
           'project-create',
           'project-edit',
           'project-delete',
           'client-list',
           'client-create',
           'client-edit',
           'client-delete',
           'client-list',
           'client-create',
           'client-edit',
           'client-delete',
           'client-list',
           'client-create',
           'client-edit',
           'client-delete',
           'client-list',
           'client-create',
           'client-edit',
           'client-delete',
           'client-list',
           'client-create',
           'client-edit',
           'client-delete'
        ];
   
        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}
