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
use Carbon\Carbon;

class DefferedParkedDocController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $tableTitle = "Deffered/Parked Documents";
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
            ->where('status_id', 7)
            ->orderBy('id', 'desc')
            ->paginate(1000);

       $this->autoParkDefferedDocument();
           
  
      return view("dts.deffered-parked-docs", compact('tableTitle', 'mySection', 'documents', 'myAllSections', 'systemSetting'));
    }


    private function autoParkDefferedDocument()
    {
        $systemSetting =DtsSystemSetting::first();
        if($systemSetting->allow_auto_park == TRUE){              
          $parked=  DB::table('dts_doc_routes')
                     ->where('status_id',7)
                     ->where('for_section_id', Auth::user()->section_id)
                     ->where('deferred_date','<',Carbon::now()->subDays(  $systemSetting->auto_parkdays)->startOfDay())
                     ->update(['status_id' => 8, 'date_parked' => Carbon::now(), 'end_remarks' => 'Parked due to long delay to take action']);
        if($parked){
            return true;
        } else {
            return false;
        }
            }else{
                return false;
            }
        }

    
}
