<?php

namespace Database\Seeders;

use App\Models\DtsSystemSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DtsSystemSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = [
            [
                'id'    => 1,
                'org_dts_code' => '12',
                'custom_system_name'=>'DepEd Davao del Norte Document Tracking System',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
           
        ];

        DtsSystemSetting::insert($setting);
    }
}
