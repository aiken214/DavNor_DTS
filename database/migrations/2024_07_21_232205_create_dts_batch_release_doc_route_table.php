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
        Schema::create('dts_batch_release_doc_route', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_release_id');
            $table->unsignedBigInteger('doc_route_id');
            $table->timestamps();        
            $table->foreign('batch_release_id')->references('id')->on('dts_batch_releases');
            $table->foreign('doc_route_id')->references('id')->on('dts_doc_routes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dts_batch_release_doc_route');
    }
};
