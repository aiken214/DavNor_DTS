<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            [
                'id'             => 1,
                'name'           => 'DTS System Admin ',
                'email'          => 'system.admin@deped.gov.ph',
                'password'       => bcrypt('pass6789'),
                'section_id'     => 5,
                'remember_token' => null,
                'created_at'     =>date('Y-m-d H:i:s'),
            ],
        ];

        User::insert($user);

        
    }
}
