<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        // Permission::create(['name' => 'edit articles']);
        // Permission::create(['name' => 'delete articles']);
        // Permission::create(['name' => 'publish articles']);
        // Permission::create(['name' => 'unpublish articles']);

        // create roles and assign created permissions

        $role = Role::create(['name' => 'OWNER']);
        $role = Role::create(['name' => 'HOB']);
        $role = Role::create(['name' => 'CASHIER']);
        $role = Role::create(['name' => 'CHEF']);
        $role = Role::create(['name' => 'SUPERADMIN']);
        $role = Role::create(['name' => 'EMPLOYEE']);

        // $role->givePermissionTo(Permission::all());
    }
}
