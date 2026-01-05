<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DtsSection;

class DtsSectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'id'=> 1,
                'mainsection_id' => 1,
                'name' => "GUEST",
                'is_dropdown' => 0,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 2,
                'mainsection_id' => 2,
                'name' => "Personnel Selection Board",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 3,
                'mainsection_id' => 3,
                'name' => "Records Section",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 4,
                'mainsection_id' => 4,
                'name' => "Accounting",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 5,
                'mainsection_id' => 5,
                'name' => "IT Unit",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 6,
                'mainsection_id' => 6,
                'name' => "SDS Office",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 7,
                'mainsection_id' => 7,
                'name' => "Legal Unit",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 8,
                'mainsection_id' => 8,
                'name' => "SGOD Office",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 9,
                'mainsection_id' => 9,
                'name' => "HRMO Office",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 10,
                'mainsection_id' => 10,
                'name' => "Budget",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 11,
                'mainsection_id' => 11,
                'name' => "Assistant SDS Office",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 12,
                'mainsection_id' => 12,
                'name' => "CID Office",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 13,
                'mainsection_id' => 13,
                'name' => "Supply Office",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 14,
                'mainsection_id' => 14,
                'name' => "ALS Office",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 15,
                'mainsection_id' => 15,
                'name' => "Cash Section",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 16,
                'mainsection_id' => 16,
                'name' => "Medical Office",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 17,
                'mainsection_id' => 17,
                'name' => "School Personnel Group",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 18,
                'mainsection_id' => 18,
                'name' => "PRME Section",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 19,
                'mainsection_id' => 19,
                'name' => "BAC Secretariat",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
              [
                'id'=> 20,
                'mainsection_id' => 20,
                'name' => "Admin Office",
                'is_dropdown' => 1,
                'created_at' =>date('Y-m-d H:i:s'),
              ],
        ];

        DtsSection::insert($sections);
    }
}
