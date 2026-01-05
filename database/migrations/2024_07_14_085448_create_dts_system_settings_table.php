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
        Schema::create('dts_system_settings', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('organization_id');
            $table->char('org_dts_code', 2)->unique();
            $table->string('custom_system_name');
            $table->integer('number_of_padding')->default(4);
            $table->boolean('allow_auto_accept');
            $table->integer('numdays_auto_accept');
     //       $table->boolean('full_qrcodescan_implementation')->default(false);
            $table->string('logo_at')->nullable();
            $table->string('logo_icon_at')->nullable();
            $table->string('logo_light_at')->nullable();
            $table->string('login_image_at')->nullable();
            $table->boolean('allow_fileupload')->default(false);
            $table->integer('route_status_id_for_viewing')->default(1);
            $table->boolean('allow_guest_docform')->default(true);
            $table->timestamps();
            // $table->foreign('organization_id')->references('id')->on('dts_organizations');
            $table->index('org_dts_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dts_system_settings');
    }
};
