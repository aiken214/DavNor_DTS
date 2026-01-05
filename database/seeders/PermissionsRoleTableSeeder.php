<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionsRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin_permissions = Permission::all();
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));
        $user_permissions = $admin_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 5) != 'user_' && substr($permission->title, 0, 5) != 'role_' && substr($permission->title, 0, 11) != 'permission_';
        });
        Role::findOrFail(2)->permissions()->sync($user_permissions);
         $dts_user_permissions = $admin_permissions->filter(function ($permission) {
        $permissionId = $permission->id;
        return ($permissionId >= 17 && $permissionId <= 37) ||  ($permissionId >= 43 && $permissionId <= 62);
    });
        Role::findOrFail(3)->permissions()->sync($dts_user_permissions);
        $dts_records_section_user_permissions = $admin_permissions->filter(function ($permission) {
            $permissionId = $permission->id;
            return ($permissionId >= 17 && $permissionId <= 62);
        });
        Role::findOrFail(4)->permissions()->sync($dts_records_section_user_permissions);       

    }
}

