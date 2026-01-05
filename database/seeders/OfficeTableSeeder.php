<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offices=[
            [
                'id' => 1,
                'name' => 'Division Office',
                'code' => 'DO',
                'is_main' => true,
            ],
        ];
        
        DB::table('offices')->insert($offices);
    }
}
