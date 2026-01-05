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
        Schema::create('dts_doc_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('file_at')->nullable();
            $table->boolean('has_doctracking')->nullable();
            $table->unsignedBigInteger('dts_document_id')->nullable();
            $table->timestamps();
            $table->foreign('dts_document_id')->references('id')->on('dts_documents');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dts_doc_attachments');
    }
};
