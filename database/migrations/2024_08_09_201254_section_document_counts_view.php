<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop the view if it exists
        DB::statement("DROP VIEW IF EXISTS `section_document_counts`;");

        // Drop the table if it exists
        if (Schema::hasTable('section_document_counts')) {
            Schema::drop('section_document_counts');
        }

        if (DB::getDriverName() !== 'sqlite') {
        DB::statement("
            CREATE VIEW `section_document_counts` AS
            SELECT `section_id`, 
                   SUM(count_incomming) AS count_incomming,
                   SUM(count_received) AS count_received,
                   SUM(count_forwarded) AS count_forwarded,
                   SUM(count_deferred) AS count_deferred,
                   SUM(guestdoc_count) AS guestdoc_count,
                   SUM(reentered_count) AS reentered_count,
                   SUM(parked_incoming_count) AS parked_incoming_count,
                   SUM(parked_pending_count) AS parked_pending_count,
                   SUM(forwardedroute_status_count) AS forwardedroute_status_count
            FROM (
                SELECT `for_section_id` AS section_id, 
                       COUNT(*) AS count_incomming, 
                       0 AS count_received, 
                       0 AS count_forwarded,
                       0 AS count_deferred,
                       0 AS guestdoc_count,
                       0 AS reentered_count,
                       0 AS parked_incoming_count,
                       0 AS parked_pending_count,
                       0 AS forwardedroute_status_count
                FROM `dts_doc_routes`
                WHERE `status_id`=1
                AND `date_accepted` IS NULL 
                AND `deleted_at` IS NULL
                GROUP BY `for_section_id`
                
                UNION ALL
                
                SELECT `for_section_id` AS section_id, 
                       0 AS count_incomming, 
                       COUNT(*) AS count_received, 
                       0 AS count_forwarded,
                       0 AS count_deferred,
                       0 AS guestdoc_count,
                       0 AS reentered_count,
                       0 AS parked_incoming_count,
                       0 AS parked_pending_count,
                       0 AS forwardedroute_status_count
                FROM `dts_doc_routes`
                WHERE `status_id`=2
                AND `deleted_at` IS NULL
                GROUP BY `for_section_id`
                
                UNION ALL
                
                SELECT `from_section_id` AS section_id, 
                       0 AS count_incomming, 
                       0 AS count_received, 
                       COUNT(*) AS count_forwarded,
                       0 AS count_deferred,
                       0 AS guestdoc_count,
                       0 AS reentered_count,
                       0 AS parked_incoming_count,
                       0 AS parked_pending_count,
                       0 AS forwardedroute_status_count
                FROM `dts_doc_routes`
                WHERE `date_accepted` IS NULL 
                AND `deleted_at` IS NULL
                GROUP BY `from_section_id`
                
                UNION ALL

                SELECT `for_section_id` AS section_id, 
                       0 AS count_incomming, 
                       0 AS count_received, 
                       0 AS count_forwarded,
                       COUNT(*) AS count_deferred,
                       0 AS guestdoc_count,
                       0 AS reentered_count,
                       0 AS parked_incoming_count,
                       0 AS parked_pending_count,
                       0 AS forwardedroute_status_count
                FROM `dts_doc_routes`
                WHERE `date_accepted` IS NOT NULL 
                AND `deleted_at` IS NULL 
                AND `status_id`=5
                GROUP BY `for_section_id`

                UNION ALL

                SELECT `receiver_section_id` AS section_id,
                       0 AS count_incomming,
                       0 AS count_received,
                       0 AS count_forwarded,
                       0 AS count_deferred,
                       COUNT(*) AS guestdoc_count,
                       0 AS reentered_count,
                       0 AS parked_incoming_count,
                       0 AS parked_pending_count,
                       0 AS forwardedroute_status_count
                FROM `dts_guestdocuments`
                WHERE `is_accepted` = FALSE
                AND `deleted_at` IS NULL
                GROUP BY `receiver_section_id`

                UNION ALL

                SELECT `for_section_id` AS section_id, 
                       0 AS count_incomming, 
                       0 AS count_received, 
                       0 AS count_forwarded,
                       0 AS count_deferred,
                       0 AS guestdoc_count,
                       COUNT(*) AS reentered_count,
                       0 AS parked_incoming_count,
                       0 AS parked_pending_count,
                       0 AS forwardedroute_status_count
                FROM `dts_doc_routes`
                WHERE `date_accepted` IS NOT NULL 
                AND `deleted_at` IS NULL 
                AND `status_id`=9
                GROUP BY `for_section_id`

                UNION ALL

                SELECT `for_section_id` AS section_id, 
                       0 AS count_incomming, 
                       0 AS count_received, 
                       0 AS count_forwarded,
                       0 AS count_deferred,
                       0 AS guestdoc_count,
                       0 AS reentered_count,
                       COUNT(*) AS parked_incoming_count,
                       0 AS parked_pending_count,
                       0 AS forwardedroute_status_count
                FROM `dts_doc_routes`
                WHERE `date_accepted` IS NOT NULL 
                AND `deleted_at` IS NULL 
                AND `status_id`=7
                GROUP BY `for_section_id`

                UNION ALL

                SELECT `for_section_id` AS section_id, 
                       0 AS count_incomming, 
                       0 AS count_received, 
                       0 AS count_forwarded,
                       0 AS count_deferred,
                       0 AS guestdoc_count,
                       0 AS reentered_count,
                       0 AS parked_incoming_count,
                       COUNT(*) AS parked_pending_count,
                       0 AS forwardedroute_status_count
                FROM `dts_doc_routes`
                WHERE `date_accepted` IS NOT NULL 
                AND `deleted_at` IS NULL 
                AND `status_id`=8
                GROUP BY `for_section_id`

                UNION ALL

                SELECT `for_section_id` AS section_id, 
                       0 AS count_incomming, 
                       0 AS count_received, 
                       0 AS count_forwarded,
                       0 AS count_deferred,
                       0 AS guestdoc_count,
                       0 AS reentered_count,
                       0 AS parked_incoming_count,
                       0 AS parked_pending_count,
                       COUNT(*) AS forwardedroute_status_count
                FROM `dts_doc_routes`
                WHERE `date_accepted` IS NOT NULL 
                AND `deleted_at` IS NULL 
                AND `status_id`=6
                GROUP BY `for_section_id`
            ) AS combined
            GROUP BY `section_id`;
        ");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the view if it exists
        DB::statement("DROP VIEW IF EXISTS `section_document_counts`;");
    }
};
