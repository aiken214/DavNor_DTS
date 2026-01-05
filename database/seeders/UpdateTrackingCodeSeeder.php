<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateTrackingCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Fetch all records with a tracking_code shorter than 11 characters
         $documents = DB::table('dts_documents')
         ->whereRaw('LENGTH(tracking_code) < 11')
         ->get();

     // Loop through each document and update the tracking_code
     foreach ($documents as $document) {
         // Calculate the number of characters needed to reach 11 after appending 'A'
         $totalLength = strlen($document->tracking_code) + 1; // +1 for the 'A'
         $zerosNeeded = 11 - $totalLength;

         // Ensure that 'A' is added after the tracking_code and fill with zeros
         $newTrackingCode = $document->tracking_code . 'A' . str_repeat('0', $zerosNeeded);

         // Ensure the new tracking_code is unique
         while (DB::table('dts_documents')->where('tracking_code', $newTrackingCode)->exists()) {
             // Increment the last digit to avoid duplication
             $newTrackingCode = substr($newTrackingCode, 0, -1) . ((int)substr($newTrackingCode, -1) + 1);
         }

         // Update the record with the new tracking_code
         DB::table('dts_documents')
             ->where('id', $document->id)
             ->update(['tracking_code' => $newTrackingCode]);
     }
    }
}
