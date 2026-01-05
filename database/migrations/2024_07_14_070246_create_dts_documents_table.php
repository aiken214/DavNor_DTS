<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dts_documents', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code')->unique()->index();
            $table->string('mo_yr')->nullable();
            $table->integer('issued_num')->nullable();
            $table->text('description');
            $table->unsignedBigInteger('guestdoc_id')->nullable();
            $table->unsignedBigInteger('dts_doc_type_id')->nullable();
            $table->unsignedBigInteger('tracking_issuedby_id')->nullable();
            $table->unsignedBigInteger('fromuser_id')->nullable();
            $table->unsignedBigInteger('from_section_id')->nullable();
            $table->string('guest_origin_name')->nullable();
            $table->string('guest_origin_organization')->nullable();           
            $table->string('logbook_page')->nullable();
            $table->timestamp('datetime_first_accepted')->nullable();
            $table->string('actions_needed')->nullable();
            $table->string('file_at')->nullable();
            $table->unsignedBigInteger('status_id')->default(1); // 1 is for active
            $table->string('old_track')->nullable()->index(); // for migration purpose only from the old dts. You may comment this out if you have no migration
            $table->integer('is_active')->nullable(); // for migration purpose only from the old dts. You may comment this out if you have no migration
            $table->boolean('for_archived')->default(0);
            $table->boolean('is_archived')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('dts_doc_type_id')->references('id')->on('dts_doc_types');
         //   $table->foreign('tracking_issuedby_id')->references('id')->on('users');
            $table->foreign('fromuser_id')->references('id')->on('users');
            $table->foreign('from_section_id')->references('id')->on('dts_sections');
     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dts_documents');
    }
};
