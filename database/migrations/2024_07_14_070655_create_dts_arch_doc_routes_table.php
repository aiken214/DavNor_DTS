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
        Schema::create('dts_arch_doc_routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prev_docroute_id')->index();
            $table->unsignedBigInteger('dts_document_id')->index();
            $table->unsignedBigInteger('previous_route_id')->index();
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->unsignedBigInteger('from_section_id')->nullable();
            $table->unsignedBigInteger('for_section_id')->nullable();
            $table->unsignedBigInteger('for_user_id')->nullable();
            $table->unsignedBigInteger('receiver_user_id')->nullable();
            $table->text('route_purpose')->nullable();
            $table->text('accepting_remarks')->nullable();
            $table->text('actions_taken')->nullable();           
            $table->unsignedBigInteger('actedby_user_id')->nullable();
            $table->dateTime('date_forwarded')->nullable();
            $table->dateTime('date_accepted')->nullable();          
            $table->datetime('date_acted')->nullable();
            $table->unsignedTinyInteger('io_type')->nullable();
            $table->unsignedTinyInteger('fwd_io_type')->nullable();
            $table->unsignedTinyInteger('status_id')->nullable()->index();
            $table->text('deferred_reason')->nullable();
            $table->dateTime('deferred_date')->nullable(); 
            $table->string('out_released_to')->nullable();   
            $table->string('logbook_page')->nullable();
            $table->string('del_reason')->nullable(); 
            $table->string('end_remarks')->nullable(); 
            $table->dateTime('autoaction_date')->nullable();
            $table->integer('oldstatus')->nullable()->index(); // is route_accomplished -for migration purpose from the old dts. You may comment this out if you have no migration
            $table->integer('active')->nullable(); 
            $table->boolean('is_qrscanned')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dts_arch_doc_routes');
    }
};
