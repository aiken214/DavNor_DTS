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
        Schema::create('dts_guestdocuments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctype_id');
            $table->string('doc_description');
            $table->unsignedBigInteger('school_id')->nullable();
            $table->string('organization')->nullable();
            $table->unsignedBigInteger('from_section_id')->nullable();
            $table->string('submittedby')->nullable();
            $table->unsignedBigInteger('submitter_id')->nullable();
            $table->unsignedBigInteger('receiver_section_id')->index();
            $table->unsignedBigInteger('intended_receiver_id')->nullable()->index();
            $table->string('actions_needed')->nullable();
            $table->boolean('is_accepted')->default(false);
            $table->boolean('is_active')->nullable(); // use only for migration
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('doctype_id')->references('id')->on('dts_doc_types');
          //  $table->foreign('submitter_id')->references('id')->on('users');
            $table->foreign('from_section_id')->references('id')->on('dts_sections');
            $table->foreign('receiver_section_id')->references('id')->on('dts_sections');
         //   $table->foreign('intended_receiver_id')->references('id')->on('users');
         $table->index(['receiver_section_id','is_accepted'],'for_receipt_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dts_guestdocuments');
    }
};
