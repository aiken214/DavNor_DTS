<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dts_batch_submits', function (Blueprint $table) {
            $table->id();
            $table->string('batch_code')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('createdby_id');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('submittedby_id')->nullable();
            $table->dateTime('submit_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('createdby_id')->references('id')->on('users');
            $table->foreign('section_id')->references('id')->on('dts_sections');
            $table->foreign('submittedby_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dts_batch_submits');
    }
};
