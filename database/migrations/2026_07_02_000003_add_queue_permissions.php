<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            ['id' => 82, 'title' => 'dts_queue_access'],
            ['id' => 83, 'title' => 'dts_queue_manage'],
        ];

        foreach ($permissions as $perm) {
            DB::table('permissions')->insert($perm);
        }

        // Grant to role 2 (DTS Admin) and role 4 (DTS Records Section User)
        $rolePermissions = [];
        foreach ([2, 4] as $roleId) {
            foreach ([82, 83] as $permId) {
                $rolePermissions[] = ['role_id' => $roleId, 'permission_id' => $permId];
            }
        }
        DB::table('permission_role')->insert($rolePermissions);

        // Clear gate permissions cache
        DB::table('cache')->where('key', 'like', '%gate_permissions%')->delete();
    }

    public function down(): void
    {
        DB::table('permission_role')->whereIn('permission_id', [82, 83])->delete();
        DB::table('permissions')->whereIn('id', [82, 83])->delete();
    }
};
