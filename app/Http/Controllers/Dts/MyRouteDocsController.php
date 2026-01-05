<?php

namespace App\Http\Controllers\Dts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DtsSystemSetting;
use App\Models\DtsSection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DtsDocRoute;


class MyRouteDocsController extends Controller
{
    
    public function routedForMe(){
        $tableTitle = 'Documents Routed For Me';
        $mySection = NULL;
        $systemSetting =DtsSystemSetting::first();
        $assignedSection=DtsSection::where('id', Auth::user()->section_id)->first();
        if ($assignedSection) {
            $mySection = $assignedSection->name;
        }
         $myAllSections= DB::table('section_user')->join('dts_sections', 'dts_sections.id','=','section_user.section_id')
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('name','asc')
                        ->get();
        $currentUserStationId = Auth::user()->section_id;   
        $documents = DtsDocRoute::with(['document', 'fromSection', 'fromUser'])
        ->where('for_section_id', Auth::user()->section_id)
        ->where('for_user_id', Auth::user()->id)
        ->where('status_id', 1)
        ->orderBy('id', 'desc')
        ->paginate(1000);          

  return view("dts.incoming-docs", compact('tableTitle', 'mySection', 'documents', 'myAllSections', 'systemSetting'));
    }


    public function acceptedByMe(){
        $tableTitle = 'Documents Accepted/Received By Me';
        $mySection = NULL;
        $systemSetting =DtsSystemSetting::first();
        $assignedSection=DtsSection::where('id', Auth::user()->section_id)->first();
        if ($assignedSection) {
            $mySection = $assignedSection->name;
        }
         $myAllSections= DB::table('section_user')->join('dts_sections', 'dts_sections.id','=','section_user.section_id')
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('name','asc')
                        ->get();
        $currentUserStationId = Auth::user()->section_id;   
        $documents = DtsDocRoute::with(['document', 'fromSection', 'fromUser'])
        ->where('for_section_id', Auth::user()->section_id)
        ->where('receiver_user_id', Auth::user()->id)
        ->where('status_id', 2)
        ->paginate(1000);

        return view("dts.received-docs", compact('tableTitle','documents', 'sections','mySection', 'myAllSections','assignedSection', 'systemSetting'));

    }

}
