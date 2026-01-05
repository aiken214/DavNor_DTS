<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DtsSectionCategory;

class DtsSectionCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $sectionCategories = [
             ['id' => 1, 'name' => 'Part of the Main Office', 'created_at' =>date('Y-m-d H:i:s')],
             ['id' => 2, 'name' => 'Field Office', 'created_at' =>date('Y-m-d H:i:s')],           
        ];
        DtsSectionCategory::insert($sectionCategories);
    }
}
