<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DtsOrganizationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = [
            ['id' => 1, 'dts_code' => '01', 'name' => 'Davao de Oro Division', 'created_at' => now()],
            ['id' => 2, 'dts_code' => '02', 'name' => 'Davao City Division', 'created_at' => now()],
            ['id' => 3, 'dts_code' => '03', 'name' => 'Davao del Norte Division', 'created_at' => now()],
            ['id' => 4, 'dts_code' => '04', 'name' => 'Davao del Sur Division', 'created_at' => now()],
            ['id' => 5, 'dts_code' => '05', 'name' => 'Davao Occidental Division', 'created_at' => now()],
            ['id' => 6, 'dts_code' => '06', 'name' => 'Davao Occidental Division', 'created_at' => now()],
            ['id' => 7, 'dts_code' => '07', 'name' => 'Digos City Division', 'created_at' => now()],
            ['id' => 8, 'dts_code' => '08', 'name' => 'IGACOS Division', 'created_at' => now()],
            ['id' => 9, 'dts_code' => '09', 'name' => 'Mati City Division', 'created_at' => now()],
            ['id' => 10, 'dts_code' => '10', 'name' => 'Panabo City Division', 'created_at' => now()],
            ['id' => 11, 'dts_code' => '11', 'name' => 'Tagum City Division', 'created_at' => now()],
            ['id' => 12, 'dts_code' => '12', 'name' => 'DepEd XI Regional Office', 'created_at' => now()],
            ['id' => 13, 'dts_code' => '13', 'name' => 'Davao del Sur Provincial Office', 'created_at' => now()],
            ['id' => 14, 'dts_code' => '14', 'name' => 'Digos City Office', 'created_at' => now()],      
      
        ];

        DB::table('dts_organizations')->insert($organizations);
    }
}
