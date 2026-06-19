<?php

namespace App\Http\Controllers\Dts;

use App\Http\Controllers\Controller;
use App\Models\DtsDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DtsSystemSetting;
use App\Models\DtsSection;
use App\Models\DtsDocRoute;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use PDF;



class MyDocumentController extends Controller
{
    
    public function index()
    {
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
        $currentUserStationId = Auth::user()->section_id;   
        $mydocuments = DB::table('dts_documents')
            ->leftJoin('dts_doc_types', 'dts_documents.dts_doc_type_id', '=', 'dts_doc_types.id')
            ->leftJoin(DB::raw('(SELECT * FROM dts_doc_routes WHERE deleted_at IS NULL AND id IN (SELECT MAX(id) FROM dts_doc_routes GROUP BY dts_document_id)) as latest_routes'), 'dts_documents.id', '=', 'latest_routes.dts_document_id')
            ->leftJoin('dts_sections', 'latest_routes.for_section_id', '=', 'dts_sections.id')
            ->leftJoin('users as rcvusers', 'latest_routes.receiver_user_id', '=', 'rcvusers.id')
            ->select(
                'dts_documents.*', 
                'dts_doc_types.description as doctype_description', 
                'latest_routes.id as latest_route_id', 
                'latest_routes.for_section_id', 
                'latest_routes.from_section_id', 
                'latest_routes.from_user_id', 
                'latest_routes.for_user_id', 
                'latest_routes.receiver_user_id', 
                'latest_routes.actedby_user_id', 
                'latest_routes.created_at as route_created_at', 
                'latest_routes.updated_at as route_updated_at', 
                'latest_routes.deleted_at as route_deleted_at', 
                'dts_sections.name as route_to_section_name',
                'rcvusers.name as receiver_name'
            )
            ->where('dts_documents.fromuser_id', Auth::user()->id)
            ->whereNull('dts_documents.deleted_at')
            ->orderBy('dts_documents.id', 'desc')
            ->paginate(10);


         return view('dts.mydocument.index', compact('mydocuments', 'tableTitle', 'mySection', 'systemSetting', 'myAllSections'));
    }


    public function viewMyDoc($docId)
    {
        $document = DtsDocument::findOrFail($docId);
        if($document->fromuser_id != Auth::user()->id){
          //  return redirect()->route('dts.mydocument.index')->with('error', 'You are not allowed to view this document.');
          return abort(403, 'You are not allowed to view this document.');
        }
        $qrCodes['styleRound'] = QrCode::size(120)->style('round')->generate($document->tracking_code);
        $docRoutes= DtsDocRoute:: with('fromUser','fromSection','forSection','receiverUser','actedByUser')
                    ->where('dts_document_id', $docId)
                    ->get();
        $docTypes = DB::table('dts_doc_types')->get();
        return view('dts.mydocument.show-mydocument', compact('document',  'docRoutes', 'qrCodes', 'docTypes'));
    }


    public function myPrintSlip($docId){
      
        $systemSetting =DtsSystemSetting::first();
             $document = DtsDocument::findOrFail($docId);
             if($document->fromuser_id != Auth::user()->id){
                          return abort(403, 'You are not allowed to view this document.');
              }
                  $data = [
                'document' => $document,
                'systemSetting' => $systemSetting,
              ];
              //Set a Custom Paper Size to 74mm x 105mm (or equivalent in inches: 2.91in x 4.13in) 1/4 of a short bond || paper->setPaper([0, 0, 4.5 * 72, 6 * 72], 'portrait');
            $pdf = PDF::loadView('dts.qr-slip', $data);
            return $pdf->stream('dts-qrslip.pdf');
        }
        
    
        public function myPrintTopRight($docId){
           
            $document = DtsDocument::findOrFail($docId);
            if($document->fromuser_id != Auth::user()->id){
                return abort(403, 'You are not allowed to view this document.');
            }
            $data = [
                'document' => $document,
            ];
            $pdf = PDF::loadView('dts.qr-top-right', $data);
            return $pdf->stream('dts-qr-topright.pdf');
        }
    
        public function myPrintBottomRight($docId){

            $document = DtsDocument::findOrFail($docId);
            if($document->fromuser_id != Auth::user()->id){
                return abort(403, 'You are not allowed to print this document.');
            }
            $data = [
                'document' => $document,
            ];
            $pdf = PDF::loadView('dts.qr-bottom-right', $data);
            return $pdf->stream('dts-qr-bottomright.pdf');
        }
    
        public function myPrintBottomLeft($docId){
           
            $document = DtsDocument::findOrFail($docId);
            if($document->fromuser_id != Auth::user()->id){
                return abort(403, 'You are not allowed to print this document.');
    }
            $data = [
                'document' => $document,
            ];
            $pdf = PDF::loadView('dts.qr-bottom-left', $data);
            return $pdf->stream('dts-qr-bottomleft.pdf');
        }
    



}



 