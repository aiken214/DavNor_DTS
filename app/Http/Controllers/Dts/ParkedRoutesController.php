<?php

namespace App\Http\Controllers\Dts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DtsDocRoute;
use App\Models\DtsSection;
use App\Models\DtsSystemSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ParkedRoutesController extends Controller
{
   

    public function incomingParked()
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $tableTitle = "Incoming Parked Document: This document has been forwarded within the system but has not yet been received. It is currently parked due to a prolonged delay in taking action.";
        $systemSetting =DtsSystemSetting::first();
        $mySection = NULL;
        $assignedSection=DtsSection::where('id', Auth::user()->section_id)->first();
        if ($assignedSection) {
            $mySection = $assignedSection->name;
        }
         $myAllSections= DB::table('section_user')->join('dts_sections', 'dts_sections.id','=','section_user.section_id')
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('name','asc')
                        ->get();
        $documentRoutes = DtsDocRoute::with(['document', 'fromSection', 'fromUser'])
            ->where('for_section_id', Auth::user()->section_id)
            ->whereNull('date_accepted')
            ->where('status_id', 7)
            ->orderBy('id', 'desc')
            ->paginate(1000);

      
        return view('dts.parked.incoming-parked', compact('tableTitle', 'mySection', 'documentRoutes', 'myAllSections', 'systemSetting'));
   
    }



    public function pendingParked()
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $tableTitle = "Pending Parked Document (Received in the system, parked due to long delay to take action.)";
        $systemSetting =DtsSystemSetting::first();
        $mySection = NULL;
        $assignedSection=DtsSection::where('id', Auth::user()->section_id)->first();
        if ($assignedSection) {
            $mySection = $assignedSection->name;
        }
         $myAllSections= DB::table('section_user')->join('dts_sections', 'dts_sections.id','=','section_user.section_id')
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('name','asc')
                        ->get();
            $documents = DtsDocRoute::with(['document', 'fromSection', 'fromUser'])
                        ->where('for_section_id', Auth::user()->section_id)
                        ->where('status_id', 8)
                        ->orderBy('id', 'desc')
                        ->paginate(1000);
        $sections=DtsSection::all();

        return view("dts.parked.pending-parked", compact('tableTitle', 'mySection', 'documents', 'sections','myAllSections', 'systemSetting'));
    }


    public function deferredParked(){

        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $tableTitle = "Deffered Parked Document (Deffered in the system, parked due to long delay to take action.)";
        $systemSetting =DtsSystemSetting::first();
        $mySection = NULL;
        $assignedSection=DtsSection::where('id', Auth::user()->section_id)->first();
        if ($assignedSection) {
            $mySection = $assignedSection->name;
        }
         $myAllSections= DB::table('section_user')->join('dts_sections', 'dts_sections.id','=','section_user.section_id')
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('name','asc')
                        ->get();
            $docRoutes = DtsDocRoute::with(['document', 'fromSection', 'fromUser'])
                        ->where('for_section_id', Auth::user()->section_id)
                        ->where('status_id', 9)
                        ->orderBy('id', 'desc')
                        ->paginate(1000);
        $sections=DtsSection::all();

        return view("dts.parked.deferred-parked", compact('tableTitle', 'mySection', 'docRoutes', 'myAllSections', 'systemSetting', 'sections'));
    }
    
}
