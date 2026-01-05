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
        Schema::create('dts_doc_types', function (Blueprint $table) {
            $table->id();
            $table->string('description')->unique();
            $table->integer('menu_sequence')->default(1);
            $table->boolean('for_guest')->default(true);
            $table->integer('form_type')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dts_doc_types');
    }
};
