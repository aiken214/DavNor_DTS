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
        if (!Schema::hasColumn('dts_doc_routes', 'is_qr_accept') || !Schema::hasColumn('dts_doc_routes', 'date_parked')) {
            Schema::table('dts_doc_routes', function (Blueprint $table) {
                if (!Schema::hasColumn('dts_doc_routes', 'is_qr_accept')) {
                    $table->boolean('is_qr_accept')->default(false);
                }

                if (!Schema::hasColumn('dts_doc_routes', 'date_parked')) {
                    $table->date('date_parked')->nullable()->after('autoaction_date');
                }
            });
        }
       


        // add the column (allow_guest_docform) for the dts_system_settings table if does not exist
        Schema::table('dts_system_settings', function (Blueprint $table) {
            //drop the column if exists 	allow_auto_accept, numdays_auto_accept and full_qrcodescan_implementation	
            if (Schema::hasColumn('dts_system_settings', 'allow_auto_accept')) {
                $table->dropColumn('allow_auto_accept');
            }
            if (Schema::hasColumn('dts_system_settings', 'numdays_auto_accept')) {
                $table->dropColumn('numdays_auto_accept');
            }
            if (Schema::hasColumn('dts_system_settings', 'full_qrcodescan_implementation')) {
                $table->dropColumn('full_qrcodescan_implementation');
            }

            if (!Schema::hasColumn('dts_system_settings', 'allow_guest_docform')) {
                $table->boolean('allow_guest_docform')->default(false)->after('allow_fileupload');
            }

           if (!Schema::hasColumn('dts_system_settings', 'allow_auto_park')) {
                $table->boolean('allow_auto_park')->default(false)->after('allow_guest_docform');
            }

            if (!Schema::hasColumn('dts_system_settings', 'auto_parkdays')) {
                $table->integer('auto_parkdays')->default(120)->after('allow_auto_park');
            }
        });

        // add the column for the dts_doc_types table if does not exist
        Schema::table('dts_doc_types', function (Blueprint $table) {
            if (!Schema::hasColumn('dts_doc_types', 'menu_sequence')) {
                $table->integer('menu_sequence')->default(1);
            }
        });
        
        Schema::table('dts_sections', function (Blueprint $table) {              
            if (!Schema::hasColumn('dts_sections', 'default_receiver_id')) {
            $table->unsignedBigInteger('default_receiver_id')->nullable()->after('is_record_management');
            $table->foreign('default_receiver_id', 'fksectionReceiverSetting67')->references('id')->on('users');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dts_sections', function (Blueprint $table) {   
            $table->dropForeign('fksectionReceiverSetting67');                      
            $table->dropColumn('default_receiver_id');
               
        });

        Schema::table('dts_system_settings', function (Blueprint $table) {
           
            // Then, drop the columns
            $table->dropColumn([
                'allow_guest_docform',
                'allow_auto_park',
                'auto_parkdays',
               
            ]);
        });
       
    }
};
