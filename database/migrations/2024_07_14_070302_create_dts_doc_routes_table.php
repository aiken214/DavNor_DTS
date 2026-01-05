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
        Schema::create('dts_doc_routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dts_document_id');
            $table->unsignedBigInteger('previous_route_id')->nullable();
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
            $table->dateTime('defer_until')->nullable();
            $table->string('out_released_to')->nullable();   
            $table->string('logbook_page')->nullable();
            $table->string('del_reason')->nullable(); 
            $table->string('end_remarks')->nullable(); 
            $table->dateTime('autoaction_date')->nullable();
            $table->integer('oldstatus')->nullable()->index(); // is route_accomplished -for migration purpose from the old dts. You may comment this out if you have no migration
            $table->integer('active')->nullable(); // for migration purpose from the old dts. You may comment this out if you have no migration
            $table->integer('route_accomplished')->nullable(); 
            $table->unsignedBigInteger('batch_release_id')->nullable();
            $table->boolean('is_qr_accept')->default(false);
            $table->boolean('for_archived')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('dts_document_id')->references('id')->on('dts_documents');
            $table->foreign('previous_route_id')->references('id')->on('dts_doc_routes');
            $table->foreign('from_user_id')->references('id')->on('users');
            $table->foreign('from_section_id')->references('id')->on('dts_sections');
            $table->foreign('for_section_id')->references('id')->on('dts_sections');
            $table->foreign('for_user_id')->references('id')->on('users');
            $table->foreign('receiver_user_id')->references('id')->on('users');
            $table->foreign('actedby_user_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('dts_route_statuses');
            $table->index(['for_section_id','date_accepted'],'incoming_route_index');
            $table->index(['for_section_id','date_accepted', 'date_acted'],'route_acted_index');
            $table->index(['for_section_id','status_id'],'section_status_index');
            $table->index(['from_section_id','status_id'],'fromsection_status_index');
            $table->index(['from_section_id','date_accepted'],'forward_acpt_index');
            $table->index(['for_section_id','date_accepted', 'status_id'],'route_deferred_index');
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dts_doc_routes');
    }
};
