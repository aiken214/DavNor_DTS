<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DtsDocTypes;
use Illuminate\Support\Facades\DB;

class DtsDocTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // $types = [
        try {
            DB::table('dts_doc_types')->insert([
                [
                    'id'=> 1,
                    'description' => "Regional Issuances",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 2,
                    'description' => "Authority to Travel",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 3,
                    'description' => "Procurements",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 4,
                    'description' => "Leave ",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 5,
                    'description' => "Communications (Letters, Etc.)",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 6,
                    'description' => "Division Issuances",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 7,
                    'description' => "Application Documents",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 8,
                    'description' => "Liquidation / Reimbursements",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 9,
                    'description' => "Reports",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 10,
                    'description' => "Others",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 11,
                    'description' => "Daily Time Record (DTR)",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 12,
                    'description' => "Disbursement Voucher",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 13,
                    'description' => "Central Office Issuances",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 14,
                    'description' => "Personnel Record",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 15,
                    'description' => "Reinstatement",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 16,
                    'description' => "Permits",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 17,
                    'description' => "Travel Abroad",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 18,
                    'description' => "Request for Cash Advance",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 19,
                    'description' => "Certificates",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 20,
                    'description' => "Utility Bills",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  [
                    'id'=> 21,
                    'description' => "Request for Documents",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                  
                  [
                    'id'=> 22,
                    'description' => "Project Design / Project Proposal",
                    'form_type'=> 1,
                    'created_at' =>date('Y-m-d H:i:s'),
                  ],
                   
            ]);
        } catch (QueryException $e) {
            Log::error('QueryException: ' . $e->getMessage());
            Log::error('SQL: ' . $e->getSql());
            Log::error('Bindings: ' . implode(', ', $e->getBindings()));
        }
    //    ];

      //  DtsDocTypes::insert($types);
    }
}
