<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DtsDocRoute;
use App\Models\DtsSection;
use App\Models\DtsSystemSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Gate;


class MydashboardController extends Controller
{
    public function index(){
        // Retrieve the aggregated count data for the user's section
        $tableTitle="Dashboard";
        $mySection = NULL;
        $assignedSection=DtsSection::where('id', Auth::user()->section_id)->first();
        $systemSetting =DtsSystemSetting::first();
        if ($assignedSection) {
            $mySection = $assignedSection->name;
        }
         $myAllSections= DB::table('section_user')->join('dts_sections', 'dts_sections.id','=','section_user.section_id')
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('name','asc')
                        ->get();
      

        $sectionReceivedCount = DB::table('section_received_counts')
                        ->where('section_id', Auth::user()->section_id)
                         ->first();
      

        return view("dts.dts-dashboard", compact('mySection','assignedSection','myAllSections', 'sectionReceivedCount', 'systemSetting'));

    }

    private function parkDocuments(){
           $dtsSection = DtsSection::where('id', Auth::user()->section_id)->first();
           $is_auto_park= $dtsSection->is_auto_parked;
        if($is_auto_park){               
           //AUto parked Incoming
        $incomingParked=   DB::table('dts_doc_routes')
                     ->where('status_id',1)
                     ->where('for_section_id', Auth::user()->section_id)
                     ->where('date_forwarded','<',Carbon::now()->subDays($dtsSection->auto_parkdays_incoming)->startOfDay())
                     ->update(['status_id' => 7, 'date_parked' => Carbon::now(), 'end_remarks' => 'Parked due to long delay to take action']);
        //AUto parked Pending
        $pendingParked=   DB::table('dts_doc_routes')
                     ->where('status_id',2)
                     ->where('for_section_id', Auth::user()->section_id)
                     ->where('date_accepted','<',Carbon::now()->subDays($dtsSection->auto_parkdays_pending)->startOfDay())
                     ->update(['status_id' => 8, 'date_parked' => Carbon::now(), 'end_remarks' => 'Parked due to long delay to take action']);
        //AUto parked Deffered
        $defferedParked=   DB::table('dts_doc_routes')
                     ->where('status_id',3)
                     ->where('for_section_id', Auth::user()->section_id)
                     ->where('deferred_date','<',Carbon::now()->subDays($dtsSection->auto_parkdays_deffered)->startOfDay())
                     ->update(['status_id' => 9, 'date_parked' => Carbon::now(), 'end_remarks' => 'Parked due to long delay to take action']);
        return true;
              }else{
                  return false;
              }
   
        }

    


    }
