<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\DtsRouteStatus;

class DtsRouteStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = [
            [
                'id'    => 1,
                'description' => 'Incoming-Route',
                'remarks'=>'Document has been routed to your section. You have not yet received it.',
                'created_at' =>date('Y-m-d H:i:s'),
            ],  
            [
                'id'    => 2,
                'description' => 'Received',
                'remarks'=>'Document has been received, but needs futher actions',
                'created_at' =>date('Y-m-d H:i:s'),
            ],           
            [
                'id'    => 3,
                'description' => 'Received and Filed',
                'remarks'=>'The document was kept by the user. No more routing unless re-entered',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
            [
                'id'    => 4,
                'description' => 'Released Out',
                'remarks'=>'The document was released to someone without using the system (manual releasing).',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
            [
                'id'    => 5,
                'description' => 'Deferred',
                'remarks'=>'The document is temporarily deferred for some reasons. No immediate action is to be made yet.',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
            [
                'id'    => 6,
                'description' => 'Forwarded',
                'remarks'=>'Document has been routed to other section',
                'created_at' =>date('Y-m-d H:i:s'),
            ],           
           
            [
                'id'    => 7,
                'description' => 'Parked Incoming-Route',
                'remarks'=>'The document has been in the Incoming-Route status for a long time. The system has automatically updated it to Parked Incoming-Route to decongest the Incoming-Route data view.',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
            [
                'id'    => 8,
                'description' => 'Parked Pending Status',
                'remarks'=>'Document has been in the Pending statuse for the long time. The system has automatically updated it to Parked Pending Status to decongest the Pending data view.',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
            [
                'id'    => 9,
                'description' => 'Parked Deffered Status',
                'remarks'=>'Document has been in the Deffered statuse for the long time. The system has automatically updated it to Parked Pending Status to decongest the Pending data view.',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
            [
                'id'    => 10,
                'description' => 'Re-Entered',
                'remarks'=>'The document has been re-entered into routing. It may be from a file that was in file-kept status or archived.',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
            [
                'id'    => 11,
                'description' => 'For Batch Release',
                'remarks'=>'This document if for batch Realeasing.',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
            [
                'id'    => 12,
                'description' => 'Cancelled-Deleted',
                'remarks'=>'The document has been cancelled. It is no longer in the routing system.',
                'created_at' =>date('Y-m-d H:i:s'),
            ],
            
        ];

        DtsRouteStatus::insert($status);
    }
}
