<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            ['id' => 80, 'title' => 'dts_batch_received_access'],
            ['id' => 81, 'title' => 'dts_batch_received_manage'],
        ];

        foreach ($permissions as $perm) {
            DB::table('permissions')->insert($perm);
        }

        // Grant to role 4 (DTS Records Section User)
        $rolePermissions = [];
        foreach ([80, 81] as $permId) {
            $rolePermissions[] = ['role_id' => 4, 'permission_id' => $permId];
        }
        DB::table('permission_role')->insert($rolePermissions);

        // Clear gate permissions cache
        DB::table('cache')->where('key', 'like', '%gate_permissions%')->delete();
    }

    public function down(): void
    {
        DB::table('permission_role')->whereIn('permission_id', [80, 81])->delete();
        DB::table('permissions')->whereIn('id', [80, 81])->delete();
    }
};
