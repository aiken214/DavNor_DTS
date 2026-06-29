<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dts_batch_submits', function (Blueprint $table) {
            $table->unsignedBigInteger('for_section_id')->nullable()->after('section_id');
        });
    }

    public function down(): void
    {
        Schema::table('dts_batch_submits', function (Blueprint $table) {
            $table->dropColumn('for_section_id');
        });
    }
};
