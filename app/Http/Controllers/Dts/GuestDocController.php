<?php

namespace App\Http\Controllers\Dts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DtsGuestdocument;
use App\Models\DtsSection;
use App\Models\DtsDocType;
use App\Models\DtsDocRoute;
use App\Models\DtsDocument;
use App\Models\DtsSystemSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class GuestDocController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
       $section = DtsSection::find(Auth::user()->section_id);
       abort_if(!$section || !$section->is_record_management, Response::HTTP_FORBIDDEN, '403 Forbidden');
        $tableTitle="Guest Documents for receipt";
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
        $sections = DtsSection::select('id', 'name')
                       ->where('id', '!=', Auth::user()->section_id)
                       ->where('id','>',1) // Exclude the first section for GUEST
                        ->where('is_dropdown', true)
                       ->orderBy('name')
                       ->get();   
        $docTypes = DtsDocType::select('id', 'description')->orderBy('description')->get();  
        $documents = DtsGuestdocument::with(['docType', 'fromSection', 'fromUser', 'receiverSection', 'intendedReceiver'])
            ->where('receiver_section_id', Auth::user()->section_id)
            ->where('is_accepted', FALSE)
            ->get();
       return view("dts.guest-docs", compact('tableTitle','sections', 'docTypes', 'documents','mySection', 'myAllSections', 'systemSetting'));
    }

    
    
  public function acceptGuestDoc(Request $request)
  {
    abort_if(Gate::denies('dts_document_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
      // Validate the request
    $request->validate([
        'description' => 'required',
        'actions_needed' => 'required',
        'dts_doc_type_id' => 'required',
        'tracking_issuedby_id' => 'required',
        // 'fromuser_id' => 'required',
        // 'fromsection_id' => 'required',
        'guest_doc_id' => 'required',
        'guest_origin_name' => 'required',
        'guest_origin_organization' => 'required',
    ]);
    try {
        // Start transaction
        DB::beginTransaction();
        // Generate tracking code
        $trackingInfo = DtsDocument::generateTrackingCode();
        // Create the document
        $document = new DtsDocument();
        $document->tracking_code = $trackingInfo['tracking_code'];
        $document->mo_yr = $trackingInfo['mo_yr'];
        $document->issued_num = $trackingInfo['issued_num'];
        $document->description = $request->input('description');
        $document->actions_needed = $request->input('actions_needed');
        $document->dts_doc_type_id = $request->input('dts_doc_type_id');
        $document->tracking_issuedby_id = $request->input('tracking_issuedby_id');
        $document->fromuser_id = Auth::user()->id;
        $document->from_section_id = Auth::user()->section_id; // the first receivers section Id
        $document->guest_origin_name = $request->input('guest_origin_name');
        $document->guest_origin_organization = $request->input('guest_origin_organization');
     //   $document->to_section_id = Auth::user()->section_id;
        $document->guestdoc_id = $request->input('guest_doc_id');
        $document->save();
        $fromUserId = Auth::user()->id;
        $fromSectionId = Auth::user()->section_id;
        $forSectionId= Auth::user()->section_id;
        $forUserId = Auth::user()->id;
        // Insert forward route
        $routeId = $this->insertRouteAndAccept($document->id, $forSectionId, $forUserId, $request->input('actions_needed'), $fromUserId, $fromSectionId );
        //Tagged Guest Document as accepted
        $guestDoc = DtsGuestdocument::find($request->input('guest_doc_id'));
        $guestDoc->is_accepted = TRUE;
        $guestDoc->save();
        // Commit transaction
        DB::commit();

        // Redirect with success message
        return redirect()->route('dts.show-new-created', [$document->id, $routeId])->with('success', 'Document Accepted successfully.');
    } catch (\Exception $e) {
        // Rollback transaction on error
        DB::rollBack();
       return redirect()->back()->with('error', 'An error occurred while creating the document and route.');
    }

     
  }
// for guest documents
  private function insertRouteAndAccept($documentId, $forSectionId, $forUserId, $actions_needed, $fromUserId, $fromSectionId ) //for first route
{
    $docRoute = new DtsDocRoute();
    $docRoute->dts_document_id = $documentId;
    $docRoute->for_section_id = $forSectionId;
    $docRoute->route_purpose = $actions_needed;
    $docRoute->for_user_id = $forUserId;
    $docRoute->from_user_id = $fromUserId;
    $docRoute->from_section_id = $fromSectionId;
    $docRoute->date_forwarded = date('Y-m-d H:i:s');
    $docRoute->date_accepted = date('Y-m-d H:i:s');
    $docRoute->status_id = 2;
    $docRoute->save();   
    return $docRoute->id;
}


public function destroy(Request $request)
{
        $id = $request->input('guest_document_id');
    abort_if(Gate::denies('dts_document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    $guestdocument = DtsGuestdocument::find($id);
    if ($guestdocument) {
        $guestdocument->delete();
        return redirect()->route('dts.guest-doc')->with('success', 'Document deleted successfully.');
    }
    return redirect()->route('dts.guest-doc')->with('error', 'Document not found.');    
  }

   
}
