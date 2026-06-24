<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            ['id' => 76, 'title' => 'dts_batch_submit_access'],
            ['id' => 77, 'title' => 'dts_batch_submit_create'],
            ['id' => 78, 'title' => 'dts_batch_submit_edit'],
            ['id' => 79, 'title' => 'dts_batch_submit_finalize'],
        ];

        foreach ($permissions as $perm) {
            DB::table('permissions')->insert($perm);
            DB::table('permission_role')->insert([
                'role_id' => 5,
                'permission_id' => $perm['id'],
            ]);
        }

        Cache::forget('gate_permissions');
    }

    public function down(): void
    {
        DB::table('permission_role')->whereIn('permission_id', [76, 77, 78, 79])->delete();
        DB::table('permissions')->whereIn('id', [76, 77, 78, 79])->delete();
        Cache::forget('gate_permissions');
    }
};
