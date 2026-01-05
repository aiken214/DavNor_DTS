<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   
    
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS doc_route_yearcount');
        if (Schema::hasTable('doc_route_yearcount')) {
            Schema::drop('doc_route_yearcount');
        }

        DB::statement('
        CREATE VIEW doc_route_yearcount AS
        SELECT 
            for_section_id,
            YEAR(date_accepted) AS year,
            COUNT(*) AS count
        FROM 
            dts_doc_routes
        WHERE 
            date_accepted IS NOT NULL
        GROUP BY 
            for_section_id, 
            YEAR(date_accepted)
        ORDER BY 
            for_section_id, 
            year;
    ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS document_route_count');
    }
    
};
