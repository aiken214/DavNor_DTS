<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create DTS School User role
        DB::table('roles')->insert([
            'id' => 5,
            'title' => 'DTS School User',
            'created_at' => now(),
        ]);

        // 2. Create dts_incoming_access permission
        DB::table('permissions')->insert([
            'id' => 75,
            'title' => 'dts_incoming_access',
            'group' => 2,
            'remarks' => 'access to incoming-route page',
            'created_at' => now(),
        ]);

        // 3. Grant dts_incoming_access to existing DTS roles (1=SysAdmin, 2=DTS Admin, 3=DTS User, 4=DTS Records Section User)
        foreach ([1, 2, 3, 4] as $roleId) {
            DB::table('permission_role')->insert([
                'role_id' => $roleId,
                'permission_id' => 75,
            ]);
        }

        // 4. Copy DTS User (role 3) permissions to DTS School User (role 5), EXCEPT dts_incoming_access (75)
        $dtsUserPermissions = DB::table('permission_role')
            ->where('role_id', 3)
            ->pluck('permission_id')
            ->toArray();

        foreach ($dtsUserPermissions as $permissionId) {
            if (in_array($permissionId, [48, 49, 75])) {
                continue;
            }
            DB::table('permission_role')->insert([
                'role_id' => 5,
                'permission_id' => $permissionId,
            ]);
        }

        Cache::forget('gate_permissions');
    }

    public function down(): void
    {
        DB::table('permission_role')->where('role_id', 5)->delete();
        DB::table('permission_role')->where('permission_id', 75)->delete();
        DB::table('roles')->where('id', 5)->delete();
        DB::table('permissions')->where('id', 75)->delete();
        Cache::forget('gate_permissions');
    }
};
