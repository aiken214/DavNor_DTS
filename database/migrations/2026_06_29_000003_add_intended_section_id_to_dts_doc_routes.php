<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dts_doc_routes', function (Blueprint $table) {
            $table->unsignedBigInteger('intended_section_id')->nullable()->after('pigeonhole_id');
        });
    }

    public function down(): void
    {
        Schema::table('dts_doc_routes', function (Blueprint $table) {
            $table->dropColumn('intended_section_id');
        });
    }
};
