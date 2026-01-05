<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DtsDocStatus;

class DocStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = [
            [
                'id'    => 1,
                'name' => 'Active',
                'description'=>'Document is currenctly in DTS',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
            [
                'id'    => 2,
                'name' => 'For Archived',
                'description'=>'For Data House Keeping and All routes are archived to decongest the database.',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
            [
                'id'    => 3,
                'name' => 'Document Digital File Disposed ',
                'description'=>'Uploaded Files are physically deleted and All routes are archived to decongest the database.',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
            [
                'id'    => 4,
                'name' => 'Archived ',
                'description'=>'All routes are archived to decongest the database.',
                'created_at' =>date('Y-m-d H:i:s'),
            ]
            ];
            DtsDocStatus::insert($status);
    }
}
