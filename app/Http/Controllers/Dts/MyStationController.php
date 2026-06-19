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
use Gate;

class MyStationController extends Controller
{
   public function index()
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $tableTitle = "My Station Documents";
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
        
        $myStationStats= DB::table('section_received_counts')
                        ->where('section_id', Auth::user()->section_id)
                        ->first();
        $myStationForwardedStats= DB::table('section_forwarded_counts')
                        ->where('section_id', Auth::user()->section_id)
                        ->first();
        $receivedCount = $this->getReceivedCount() ?? NULL;
        $forwardedCount = $this->getForwardedCount() ?? NULL;
        $documentsKeptCount = $this->getDocumentsKeptCount() ?? NULL;
        $pendingCount = $this->getPendingCount() ?? NULL;

  $receivedDocsByType= $this->getDocumentReceivedCountByType();
  $forwardedDocsByType= $this->getDocumentForwardeCountByType();


        

        return view('dts.mysection.my-station', compact('tableTitle',  'myAllSections', 'systemSetting',  'mySection', 'myStationStats', 
                                 'myStationForwardedStats',
                                 'receivedCount', 'forwardedCount', 'documentsKeptCount', 'pendingCount', 'receivedDocsByType', 'forwardedDocsByType'));
    }



    public function queryDates(Request $request){
        $request->validate([
            'begin_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
        session([
            'beginDate' => $request->input('begin_date'),
            'endDate' => $request->input('end_date'),
        ]);

        return redirect()->route('dts.my-station');
    }

    public function clearDates(){
        session()->forget('beginDate');
        session()->forget('endDate');
        return redirect()->route('dts.my-station');
    }

    private function getReceivedCount(){
        $beginDate = session('beginDate');
        $endDate = session('endDate');

        if (!$beginDate || !$endDate) {
            return 0; // Return 0 if dates are not set
        }

        $receivedCount = DB::table('dts_doc_routes')
                        ->where('for_section_id', Auth::user()->section_id)
                        ->whereDate('date_accepted', '>=', $beginDate)
                        ->whereDate('date_accepted', '<=', $endDate)
                        ->whereNull('dts_doc_routes.deleted_at')
                        ->count();
        return $receivedCount;
    }

    private function getPendingCount(){
        $beginDate = session('beginDate');
        $endDate = session('endDate');

        if (!$beginDate || !$endDate) {
            return 0; // Return 0 if dates are not set
        }

        $receivedCount = DB::table('dts_doc_routes')
                        ->where('for_section_id', Auth::user()->section_id)
                        ->where(function($query) {
                            $query->where('status_id', 2)
                            ->orWhere('status_id', 5) //deffered
                            ->orWhere('status_id', 8) //parked pending
                            ->orWhere('status_id', 9); //parked deffereed
                        })
                        ->whereDate('date_accepted', '>=', $beginDate)
                        ->whereDate('date_accepted', '<=', $endDate)
                        ->whereNull('dts_doc_routes.deleted_at')
                        ->count();
        return $receivedCount;
    }

    private function getForwardedCount(){
        $beginDate = session('beginDate');
        $endDate = session('endDate');

        if (!$beginDate || !$endDate) {
            return 0; // Return 0 if dates are not set
        }

        $forwardedCount = DB::table('dts_doc_routes')
                            ->where('from_section_id', Auth::user()->section_id)
                            ->whereDate('date_forwarded', '>=', $beginDate)
                            ->whereDate('date_forwarded', '<=', $endDate)
                            ->whereNull('dts_doc_routes.deleted_at')
                            ->count();
        return $forwardedCount;
    }

    //documenst Kept status id=3
    public function getDocumentsKeptCount(){
        $beginDate = session('beginDate');
        $endDate = session('endDate');

        if (!$beginDate || !$endDate) {
            return 0; // Return 0 if dates are not set
        }

        $documentsKept = DB::table('dts_doc_routes')
                            ->where('for_section_id', Auth::user()->section_id)
                            ->where('status_id', 3)
                            ->whereDate('date_acted', '>=', $beginDate)
                            ->whereDate('date_acted', '<=', $endDate)
                            ->whereNull('dts_doc_routes.deleted_at')
                            ->count();
        return $documentsKept;
      
    }

    private function getDocumentReceivedCountByType(){
        $beginDate = session('beginDate');
        $endDate = session('endDate');

        if (!$beginDate || !$endDate) {
            return NuLL; // Return 0 if dates are not set
        }

        $documentCount = DB::table('dts_doc_routes')
            ->join('dts_documents', 'dts_documents.id', '=', 'dts_doc_routes.dts_document_id')
            ->join('dts_doc_types', 'dts_doc_types.id', '=', 'dts_documents.dts_doc_type_id')
            ->where('for_section_id', Auth::user()->section_id)
            ->whereDate('date_accepted', '>=', $beginDate)
            ->whereDate('date_accepted', '<=', $endDate)
            ->whereNull('dts_doc_routes.deleted_at')
            ->select('dts_doc_types.description', DB::raw('count(*) as total'))
            ->groupBy('dts_documents.dts_doc_type_id', 'dts_doc_types.description')
            ->orderBy('dts_doc_types.description')
            ->get();
        return $documentCount;
    }

    private function getDocumentForwardeCountByType(){
        $beginDate = session('beginDate');
        $endDate = session('endDate');

        if (!$beginDate || !$endDate) {
            return NuLL; // Return 0 if dates are not set
        }

        $documentCount = DB::table('dts_doc_routes')
            ->join('dts_documents', 'dts_documents.id', '=', 'dts_doc_routes.dts_document_id')
            ->join('dts_doc_types', 'dts_doc_types.id', '=', 'dts_documents.dts_doc_type_id')
            ->where('dts_doc_routes.from_section_id', Auth::user()->section_id)
            ->whereDate('dts_doc_routes.date_forwarded', '>=', $beginDate)
            ->whereDate('dts_doc_routes.date_forwarded', '<=', $endDate)
            ->whereNull('dts_doc_routes.deleted_at')
            ->select('dts_doc_types.description', DB::raw('count(*) as total'))
            ->groupBy('dts_documents.dts_doc_type_id', 'dts_doc_types.description')
            ->orderBy('dts_doc_types.description')
            ->get();
        return $documentCount;
    }

//======== VIew Document List ===========

public function viewReceivedDocuments(){
    abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    $beginDate = session('beginDate');
    $endDate = session('endDate');

        $tableTitle = "Received Documents for Date Range : " . \Carbon\Carbon::parse($beginDate)->format('F j, Y') . " to " . \Carbon\Carbon::parse($endDate)->format('F j, Y');
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
   
    if (!$beginDate || !$endDate) {
        $receivedDocs=NULL; // Return 0 if dates are not set
    }else{
    
    $receivedDocs = DtsDocRoute::with(['document', 'fromSection', 'fromUser'])
                ->where('for_section_id', Auth::user()->section_id)
               ->whereDate('date_accepted', '>=', $beginDate)
                ->whereDate('date_accepted', '<=', $endDate) 
                ->orderBy('date_accepted', 'desc')               
                ->paginate(100);
    }

    return view('dts.mysection.received-documents', compact('receivedDocs', 'tableTitle', 'systemSetting', 'myAllSections', 'mySection'));

}

// view kept documents
public function viewDocumentKept(){
    abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    $beginDate = session('beginDate');
    $endDate = session('endDate');

        $tableTitle = "Documents Kept for Date Range : " . \Carbon\Carbon::parse($beginDate)->format('F j, Y') . " to " . \Carbon\Carbon::parse($endDate)->format('F j, Y');
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
   
    if (!$beginDate || !$endDate) {
        $keptDocs=NULL; // Return 0 if dates are not set
    }else{
    
    $keptDocs = DtsDocRoute::with(['document', 'fromSection', 'fromUser'])
                ->where('for_section_id', Auth::user()->section_id)
               ->where('status_id', 3)
               ->whereDate('date_acted', '>=', $beginDate)
                ->whereDate('date_acted', '<=', $endDate) 
                ->orderBy('date_acted', 'desc')               
                ->paginate(100);
    }

    return view('dts.mysection.kept-documents', compact('keptDocs', 'tableTitle', 'systemSetting', 'myAllSections', 'mySection'));

}

// view forwarded documents

public function viewForwardedDocs(){
    abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    $beginDate = session('beginDate');
    $endDate = session('endDate');

        $tableTitle = "Forwarded Documents for Date Range : " . \Carbon\Carbon::parse($beginDate)->format('F j, Y') . " to " . \Carbon\Carbon::parse($endDate)->format('F j, Y');
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
   
    if (!$beginDate || !$endDate) {
        $forwardedDocs=NULL; // Return 0 if dates are not set
    }else{
    
    $forwardedDocs = DtsDocRoute::with(['document', 'fromSection', 'fromUser'])
                ->where('from_section_id', Auth::user()->section_id)
               ->whereDate('date_forwarded', '>=', $beginDate)
                ->whereDate('date_forwarded', '<=', $endDate) 
                ->orderBy('date_forwarded', 'desc')               
                ->paginate(100);
    }

    return view('dts.mysection.forwarded-documents', compact('forwardedDocs', 'tableTitle', 'systemSetting', 'myAllSections', 'mySection'));

}


// view pending documents

public function viewPendingDocuments(){
    abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    $beginDate = session('beginDate');
    $endDate = session('endDate');

    $tableTitle = "Pending Documents for Date Range : " . \Carbon\Carbon::parse($beginDate)->format('F j, Y') . " to " . \Carbon\Carbon::parse($endDate)->format('F j, Y');
    $systemSetting = DtsSystemSetting::first();

    $mySection = NULL;
    $assignedSection = DtsSection::where('id', Auth::user()->section_id)->first();
    if ($assignedSection) {
        $mySection = $assignedSection->name;
    }
    $myAllSections = DB::table('section_user')->join('dts_sections', 'dts_sections.id', '=', 'section_user.section_id')
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('name', 'asc')
                        ->get();
     $sections = DtsSection::select('id', 'name')
                        ->where('id', '!=', Auth::user()->section_id)
                        ->where('id','>',1) // Exclude the first section for GUEST
                        ->where('category_id', 1) // for division office
                        ->where('is_dropdown', true)
                        ->orderBy('name')
                        ->get();

    if (!$beginDate || !$endDate) {
        $pendingDocs = NULL; // Return NULL if dates are not set
    } else {
        $documents = DtsDocRoute::with(['document', 'fromSection', 'fromUser'])
                    ->where('for_section_id', Auth::user()->section_id)
                    ->where(function($query) {
                        $query->where('status_id', 2)
                              ->orWhere('status_id', 5) //deffered
                              ->orWhere('status_id', 8) //parked pending
                              ->orWhere('status_id', 9); //parked deffereed
                    })
                    ->whereDate('date_accepted', '>=', $beginDate)
                    ->whereDate('date_accepted', '<=', $endDate)
                    ->orderBy('date_accepted', 'desc')
                    ->paginate(100);
    }

    return view('dts.received-docs', compact('documents', 'tableTitle', 'systemSetting', 'myAllSections', 'mySection', 'assignedSection', 'sections'));
}



}
