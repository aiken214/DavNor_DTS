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
        Schema::create('dts_arch_docroute_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('previous_note_id');
            $table->unsignedBigInteger('dts_route_id');
            $table->text('notes');
            $table->timestamps();
            $table->softDeletes();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dts_arch_docroute_notes');
    }
};
