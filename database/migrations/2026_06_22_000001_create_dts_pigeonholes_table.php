<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dts_pigeonholes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('section_id');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('section_id')->references('id')->on('dts_sections');
        });

        Schema::table('dts_doc_routes', function (Blueprint $table) {
            $table->unsignedBigInteger('pigeonhole_id')->nullable()->after('is_qr_accept');
            $table->foreign('pigeonhole_id')->references('id')->on('dts_pigeonholes');
        });
    }

    public function down(): void
    {
        Schema::table('dts_doc_routes', function (Blueprint $table) {
            $table->dropForeign(['pigeonhole_id']);
            $table->dropColumn('pigeonhole_id');
        });

        Schema::dropIfExists('dts_pigeonholes');
    }
};
