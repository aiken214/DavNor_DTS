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
        DB::statement('DROP VIEW IF EXISTS doc_route_yearcount');
        DB::statement("DROP VIEW IF EXISTS `section_received_counts`;");

        if (Schema::hasTable('section_received_counts')) {
            Schema::drop('section_received_counts');
        }

         DB::statement("
         CREATE VIEW section_received_counts AS
            SELECT `section_id`, 
                SUM(ytd_count) AS ytd_count,
                SUM(last_year_count) AS last_year_count,
                SUM(today_count) AS today_count,
                SUM(yesterday_count) AS yesterday_count,
                SUM(week_count) AS week_count,
                SUM(last_week_count) AS last_week_count,
                SUM(month_count) AS month_count,
                SUM(last_month_count) AS last_month_count
            FROM (
                SELECT `for_section_id` AS section_id, 
                    COUNT(*) AS ytd_count, 
                    0 AS last_year_count,
                    0 AS today_count,
                    0 AS yesterday_count,
                    0 AS week_count,
                    0 AS last_week_count,
                    0 AS month_count,
                    0 AS last_month_count
                FROM `dts_doc_routes`
                WHERE YEAR(`date_accepted`) = YEAR(CURDATE())
                AND `deleted_at` IS NULL
                GROUP BY `for_section_id`
                
                UNION ALL
                
                SELECT `for_section_id` AS section_id, 
                    0 AS ytd_count, 
                    COUNT(*) AS last_year_count,
                    0 AS today_count,
                    0 AS yesterday_count,
                    0 AS week_count,
                    0 AS last_week_count,
                    0 AS month_count,
                    0 AS last_month_count
                FROM `dts_doc_routes`
                WHERE YEAR(`date_accepted`) = YEAR(CURDATE()) - 1
                AND `deleted_at` IS NULL
                GROUP BY `for_section_id`
                
                UNION ALL
                
                SELECT `for_section_id` AS section_id, 
                    0 AS ytd_count, 
                    0 AS last_year_count,
                    COUNT(*) AS today_count,
                    0 AS yesterday_count,
                    0 AS week_count,
                    0 AS last_week_count,
                    0 AS month_count,
                    0 AS last_month_count
                FROM `dts_doc_routes`
                WHERE DATE(`date_accepted`) = CURDATE()
                AND `deleted_at` IS NULL
                GROUP BY `for_section_id`
                
                UNION ALL
                
                SELECT `for_section_id` AS section_id, 
                    0 AS ytd_count, 
                    0 AS last_year_count,
                    0 AS today_count,
                    COUNT(*) AS yesterday_count,
                    0 AS week_count,
                    0 AS last_week_count,
                    0 AS month_count,
                    0 AS last_month_count
                FROM `dts_doc_routes`
                WHERE DATE(`date_accepted`) = CURDATE() - INTERVAL 1 DAY
                AND `deleted_at` IS NULL
                GROUP BY `for_section_id`
                
                UNION ALL
                
                SELECT `for_section_id` AS section_id, 
                    0 AS ytd_count, 
                    0 AS last_year_count,
                    0 AS today_count,
                    0 AS yesterday_count,
                    COUNT(*) AS week_count,
                    0 AS last_week_count,
                    0 AS month_count,
                    0 AS last_month_count
                FROM `dts_doc_routes`
                WHERE YEARWEEK(`date_accepted`, 1) = YEARWEEK(CURDATE(), 1)
                AND `deleted_at` IS NULL
                GROUP BY `for_section_id`
                
                UNION ALL
                
                SELECT `for_section_id` AS section_id, 
                    0 AS ytd_count, 
                    0 AS last_year_count,
                    0 AS today_count,
                    0 AS yesterday_count,
                    0 AS week_count,
                    COUNT(*) AS last_week_count,
                    0 AS month_count,
                    0 AS last_month_count
                FROM `dts_doc_routes`
                WHERE YEARWEEK(`date_accepted`, 1) = YEARWEEK(CURDATE() - INTERVAL 1 WEEK, 1)
                AND `deleted_at` IS NULL
                GROUP BY `for_section_id`
                
                UNION ALL
                
                SELECT `for_section_id` AS section_id, 
                    0 AS ytd_count, 
                    0 AS last_year_count,
                    0 AS today_count,
                    0 AS yesterday_count,
                    0 AS week_count,
                    0 AS last_week_count,
                    COUNT(*) AS month_count,
                    0 AS last_month_count
                FROM `dts_doc_routes`
                WHERE YEAR(`date_accepted`) = YEAR(CURDATE()) AND MONTH(`date_accepted`) = MONTH(CURDATE())
                AND `deleted_at` IS NULL
                GROUP BY `for_section_id`
                
                UNION ALL
                
                SELECT `for_section_id` AS section_id, 
                    0 AS ytd_count, 
                    0 AS last_year_count,
                    0 AS today_count,
                    0 AS yesterday_count,
                    0 AS week_count,
                    0 AS last_week_count,
                    0 AS month_count,
                    COUNT(*) AS last_month_count
                FROM `dts_doc_routes`
                WHERE YEAR(`date_accepted`) = YEAR(CURDATE()) AND MONTH(`date_accepted`) = MONTH(CURDATE() - INTERVAL 1 MONTH)
                AND `deleted_at` IS NULL
                GROUP BY `for_section_id`
            ) AS combined_counts
            GROUP BY `section_id`;
         
         ");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         // Drop the view if it exists
        DB::statement("DROP VIEW IF EXISTS `section_received_counts`;");
    }
};
