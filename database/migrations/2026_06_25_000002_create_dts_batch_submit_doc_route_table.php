<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dts_batch_submit_doc_route', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_submit_id');
            $table->unsignedBigInteger('doc_route_id');
            $table->unsignedBigInteger('dts_document_id');
            $table->timestamps();
            $table->foreign('batch_submit_id')->references('id')->on('dts_batch_submits');
            $table->foreign('doc_route_id')->references('id')->on('dts_doc_routes');
            $table->foreign('dts_document_id')->references('id')->on('dts_documents');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dts_batch_submit_doc_route');
    }
};
