<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DtsInOut;

class DtsInOutTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = [
            [
                'id'    => 1,
                'name' => 'Incomming',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
            [
                'id'    => 2,
                'name' => 'Outgoing',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
        ];

        DtsInOut::insert($status);
    }
}
