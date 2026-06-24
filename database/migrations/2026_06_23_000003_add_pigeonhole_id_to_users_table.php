<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('pigeonhole_id')->nullable()->after('section_id');
            $table->foreign('pigeonhole_id')->references('id')->on('dts_pigeonholes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['pigeonhole_id']);
            $table->dropColumn('pigeonhole_id');
        });
    }
};
