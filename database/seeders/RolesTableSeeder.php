<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id'    => 1,
                'title' => 'System Admin (IT Officer)',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
            [
                'id'    => 2,
                'title' => 'DTS Admin',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
            [
                'id'    => 3,
                'title' => 'DTS User',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
             [
                'id'    => 4,
                'title' => 'DTS Records Section User',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
        ];

        Role::insert($roles);
    }
}
