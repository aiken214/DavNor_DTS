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
        Schema::create('dts_batch_releases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->unsignedBigInteger('createdby_id');
            $table->unsignedBigInteger('section_id');  
            $table->unsignedBigInteger('releaseby_id')->nullable();   
            $table->unsignedBigInteger('school_id')->nullable();       
            $table->dateTime('release_date')->nullable();
            $table->string('receiver_name')->nullable();    
            $table->timestamps();
            $table->softDeletes();
             $table->foreign('createdby_id')->references('id')->on('users');
            $table->foreign('section_id')->references('id')->on('dts_sections');
            $table->foreign('releaseby_id')->references('id')->on('users');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dts_batch_releases');
    }
};
