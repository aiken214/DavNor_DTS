<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dts_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->default(1); // 1 for division office // 2 for schools
            $table->integer('mainsection_id')->nullable();
            $table->string('name')->unique();
            $table->unsignedBigInteger('office_id')->nullable();
            $table->boolean('is_dropdown')->default(true);
            $table->boolean('is_public_dropdown')->default(true);
            $table->boolean('is_record_management')->default(false);
            $table->unsignedBigInteger('default_user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('category_id')->references('id')->on('dts_section_categories');
            $table->foreign('office_id')->references('id')->on('offices');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dts_sections');
    }
};
