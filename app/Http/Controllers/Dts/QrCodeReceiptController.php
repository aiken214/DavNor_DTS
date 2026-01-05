<?php

namespace App\Http\Controllers\Dts;

use App\Http\Controllers\Controller;
use App\Models\DtsDocument;
use FactoryCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DtsSection;
use App\Models\DtsSystemSetting;
use App\Models\DtsDocRoute;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;



class QrCodeReceiptController extends Controller
{
    


    public function webcamScan()
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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

        return view('dts.dts-webcamscan', compact('mySection','assignedSection','myAllSections',  'systemSetting', 'sectionReceivedCount'));
    }

    
    public function searchResult(Request $request)
    {
        $validated = $request->validate([
            'doc_track' => 'required|string',
        ]);
        $qrcode =$request->doc_track;
        $result=DB::table('dts_documents')->where('tracking_code', $qrcode)->first();
       
        if ($result) {
            session(['document_id' => $result->id]);
            return redirect()->route('dts.qr-search');
        } else {
            return redirect()->back()->with('error', 'Document not found');
        }
    }


    public function qrSearch(){
        //
        $tableTitle = 'My Documents';
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
        $id = session('document_id');
      
       
        $document= DB::table('dts_documents')->where('dts_documents.id', $id)
                        ->leftJoin('dts_doc_types', 'dts_documents.dts_doc_type_id', '=', 'dts_doc_types.id')
                        ->leftJoin(DB::raw('(SELECT * FROM dts_doc_routes WHERE deleted_at IS NULL AND id IN (SELECT MAX(id) FROM dts_doc_routes GROUP BY dts_document_id)) as latest_routes'), 
                        'dts_documents.id', '=', 'latest_routes.dts_document_id'                    
                        )
                        ->leftJoin('dts_sections as from_section', 'from_section.id', '=', 'latest_routes.from_section_id')
                        ->leftJoin('dts_sections as for_section', 'for_section.id', '=', 'latest_routes.for_section_id')
                        ->select(
                           'dts_documents.*', 
                        'dts_doc_types.description as doc_type_description',
                        'latest_routes.id as latest_route_id', 
                        'latest_routes.previous_route_id',
                        'latest_routes.for_section_id as routeForSecId', 
                        'latest_routes.from_section_id as routeFromSecId', 
                        'latest_routes.from_user_id', 
                        'latest_routes.for_user_id', 
                        'latest_routes.receiver_user_id', 
                        'latest_routes.actedby_user_id', 
                        'latest_routes.date_accepted as routeDateAccepted',
                        'latest_routes.created_at as route_created_at', 
                        'latest_routes.updated_at as route_updated_at', 
                        'latest_routes.deleted_at as route_deleted_at',  
                        'from_section.name as from_section_name',
                        'for_section.name as for_section_name',                                             
                                       
                        )
                        ->first();
    
    
    
    return view('dts.dts-qrsearch-result', compact('document', 'mySection', 'myAllSections', 'tableTitle', 'systemSetting'));
        
    }


public function quickReceipt(Request $request){   
    abort_if(Gate::denies('dts_route_receive'), Response::HTTP_FORBIDDEN, '403 Forbidden');
                   
    $validated = $request->validate([
        'doc_track' => 'required|string',
    ]);
    $qrcode =$request->doc_track;
    $document= DB::table('dts_documents')->where('tracking_code', $qrcode)
                    ->leftJoin(DB::raw('(SELECT * FROM dts_doc_routes WHERE deleted_at IS NULL AND id IN (SELECT MAX(id) FROM dts_doc_routes GROUP BY dts_document_id)) as latest_routes'), 
                    'dts_documents.id', '=', 'latest_routes.dts_document_id'                    
                    )
                    ->select(
                        'dts_documents.*', 
                        'latest_routes.id as latest_route_id', 
                        'latest_routes.previous_route_id',
                        'latest_routes.for_section_id as routeForSecId', 
                        'latest_routes.from_section_id as routeFromSecId', 
                        'latest_routes.from_user_id', 
                        'latest_routes.for_user_id', 
                        'latest_routes.receiver_user_id', 
                        'latest_routes.actedby_user_id', 
                        'latest_routes.date_accepted as routeDateAccepted',
                        'latest_routes.created_at as route_created_at', 
                        'latest_routes.updated_at as route_updated_at', 
                        'latest_routes.deleted_at as route_deleted_at',                         
                    )
                    ->first();
    $message=NULL;
    $data=[];
    if($document !=NULL){
        // Set Previous Section ID  && has Route
        $origDocSectionId = $document->from_section_id;
           if($document->latest_route_id !=NULL){  // Route Existed
               //----------------------------------------------
                    //set Previous Section ID
                    if($document->previous_route_id !=NULL){
                        $prevSectionId = $this->getPreviousFromSectionId($document->previous_route_id);
                    }else{
                        $prevSectionId = $origDocSectionId;
                    } 
                    //end Previous Section ID
              //----------------------------------------   
            //check if the last route is at user section
            if(Auth::user()->section_id != $document->routeForSecId){
                // Not Routed to MySection, Just  Create New Route & Accept then update the current route to status_id 6
                $data=[
                    'dts_document_id' => $document->id,
                    'previous_route_id' => $document->latest_route_id,
                    'route_purpose' => $document->actions_needed,
                    'from_user_id' => $this->getPreviousForUserId($document->latest_route_id),  // get the previous route for_user_id
                    'from_section_id' => $prevSectionId,
                    'for_section_id' => Auth::user()->section_id,
                    'for_user_id' => Auth::user()->id,
                    'receiver_user_id' => Auth::user()->id,
                    'date_forwarded'=> date('Y-m-d H:i:s'),
                    'status_id' => 2,
                    'io_type' => 1,
                    'accepting_remarks' => 'QR Scan Acceptance',
                    'is_qr_accept' => true,
                    'date_accepted' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),

                ];
                DB::transaction(function () use ($data, $document, &$message) {
                    if($this->notDuplicateRoute($document->latest_route_id, Auth::user()->section_id, $document->id)){
                        $newRouteId = DB::table('dts_doc_routes')->insertGetId($data);
                        if($newRouteId){
                            // update the previous route to status 6
                            $dataforPrev = [
                                'status_id' => 6,
                           //     'date_acted' => date('Y-m-d H:i:s'), //for review
                                'updated_at' => date('Y-m-d H:i:s'),
                            ];
                            DB::table('dts_doc_routes')
                                ->where('id', $document->latest_route_id)

                                ->update($dataforPrev);
                        }
                    } else {
                        $message = "Route Already Exist (Case 1A);";
                    }
                });
               // $message.= "Case 1 - Document is accepted to section ID : ". Auth::user()->section_id . " : Prev SectionID : ".$prevSectionId;
                $message.= "Document is accepted to your section successfully";
            } //--end of check if the last route is at user section
            else{
                 //check if the document is already accepted
                if($document->routeDateAccepted !=NULL){
                    $routeId=$document->latest_route_id;
                    $previousRouteId = $document->previous_route_id;
                    
                  //  $message.= "Doc Route Already Accepted Date Accepted  : ".$document->routeDateAccepted . ' (Case 2)';
                    $message.= "Document is accepted to your section successfully..";
                }else{
                    // Accept the document Update the Route Date Accepted
                    $routeId=$document->latest_route_id;
                    $previousRouteId = $document->previous_route_id;

                    $data=[
                                                
                        'receiver_user_id' => Auth::user()->id,
                        'accepting_remarks' => 'QR Scan Acceptance',
                        'date_accepted' => date('Y-m-d H:i:s'),
                        'status_id' => 2,
                        'io_type' => 1,
                        'is_qr_accept' => true,                         
                        'updated_at' => date('Y-m-d H:i:s'),
    
                    ];

                    $updateRoute= DB::table('dts_doc_routes')
                        ->where('id', $routeId)
                        ->update($data);
                    
                        if($updateRoute && $previousRouteId !=NULL){
                        // update the previous route to status 6                                                  
                             $dataForPrevRoute=[
                                    'status_id' => 6,
                                 //   'date_acted' => date('Y-m-d H:i:s'), //for review
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ];
                                DB::table('dts_doc_routes')
                                ->where('id', $previousRouteId)
                                ->update($dataForPrevRoute);
                            }
                    // $message= "Case 3: Successfully Received : Prev SectionID : ".$prevSectionId;
                    $message= "Document is accepted to your section successfully...";
                }
            }     



           } else{  // No Route Recorded but Document Existed
           
            $data=[
                'dts_document_id' => $document->id,
                'previous_route_id' => NULL,
                'route_purpose' => 'For Receiving',
                'from_user_id' => $document->fromuser_id,
                'from_section_id' => $document->from_section_id,
                'for_section_id' => Auth::user()->section_id,
                'for_user_id' => Auth::user()->id,
                'receiver_user_id' => Auth::user()->id,
                'date_forwarded'=> date('Y-m-d H:i:s'),
                'status_id' => 2,
                'io_type' => 1,
                'accepting_remarks' => 'QR Scan Acceptance',
                'is_qr_accept' => true,
                'date_accepted' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),

            ];
    
     $newRouteId = DB::table('dts_doc_routes')->insertGetId($data);        
     $message= "Document is accepted to your section successfully....";

           }

    }else{
        // Document Not Found
        $message= "Document not found";
        return redirect()->back()->with('error',   $message);
    }
    //  echo $message;
    // echo "<pre>";
    // echo  print_r($data);
    // echo "</pre>";

    // dd($document);
    return redirect()->back()->with('success',   $message);

}
// Prevent Duplicate Route  
 private function notDuplicateRoute($previousRouteId, $forSectionId, $dtsDocumentId){
    $route = DB::table('dts_doc_routes')->where('previous_route_id', $previousRouteId)
    ->where('for_section_id', $forSectionId)
    ->where('dts_document_id', $dtsDocumentId) // this illeminates if previous routeID is NULL
    ->first();
    if($route){
        return false;
    }else{
        return true;
    }    
 }    
 


private function getPreviousForUserId($routeId){
    $previousSection= $this->getPreviousRoute($routeId);
        if($previousSection){
                return $previousSection->for_user_id;
            }else{
                return NULL;
            }
}

private function getPreviousFromSectionId($routeId){
    $previousSection= $this->getPreviousRoute($routeId);
        if($previousSection){
                return $previousSection->from_section_id;
            }else{
                return NULL;
            }
}


private function getPreviousRoute($routeId){
            $previousRoute = DB::table('dts_doc_routes')
            ->where('id', $routeId)
            ->whereNull('deleted_at')
            ->first();   
        
            return $previousRoute;
}





}
