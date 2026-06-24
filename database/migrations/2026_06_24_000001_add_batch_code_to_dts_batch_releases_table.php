<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dts_batch_releases', function (Blueprint $table) {
            $table->string('batch_code')->nullable()->after('id');
        });

        $batches = DB::table('dts_batch_releases')->orderBy('id')->get();
        foreach ($batches as $batch) {
            DB::table('dts_batch_releases')
                ->where('id', $batch->id)
                ->update(['batch_code' => 'BR-' . str_pad($batch->id, 5, '0', STR_PAD_LEFT)]);
        }
    }

    public function down(): void
    {
        Schema::table('dts_batch_releases', function (Blueprint $table) {
            $table->dropColumn('batch_code');
        });
    }
};
