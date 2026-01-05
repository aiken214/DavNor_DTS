<?php
namespace App\Http\Controllers\Dts;

Use App\Http\Controllers\Controller;
use App\Models\DtsDocument;
use App\Models\DtsSection;
use App\Models\DtsDocType;
use App\Models\DtsDocRoute;
use App\Models\User;
use App\Models\DtsSystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\UpdateDocumentRequest;
use Illuminate\Support\Facades\Log;

use Gate;
use Auth;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
       
       
        return view("dts.dts-dashboard", compact("tableTitle", 'mySection','assignedSection','myAllSections', 'sectionReceivedCount', 'systemSetting'));
    }

    
public function docView($docId){
    abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
        $currentUserStationId = Auth::user()->section_id;    
    $document = DtsDocument::findOrFail($docId);
    $qrCodes['styleRound'] = QrCode::size(120)->style('round')->generate($document->tracking_code);
    $docTypes= DtsDocType::all();

    $docRoutes= DtsDocRoute:: with('fromUser','fromSection','forSection','receiverUser','actedByUser')
                ->where('dts_document_id', $docId)
                ->get();
    $latestRoute = DtsDocRoute::where('dts_document_id', $docId)->orderBy('id', 'desc')->first();

    return view('dts.show-document', compact('document','docRoutes', 'mySection', 'myAllSections','assignedSection','qrCodes', 'systemSetting', 'docTypes', 'latestRoute'));

}
    public function sectionStat(){
        $docCount = DB::table('section_document_counts')
            ->where('section_id', Auth::user()->section_id)
            ->first(); 
    
        $guestdocCount = $docCount ? $docCount->guestdoc_count : 0;
        $incomingCount = $docCount ? $docCount->count_incomming : 0;
        $receivedCount = $docCount ? $docCount->count_received : 0;
        $forwardedCount = $docCount ? $docCount->count_forwarded : 0;           
        $deferredCount = $docCount ? $docCount->count_deferred : 0;
    
        // Return the data as JSON
        return response()->json([
            'guestdoc_count' => $guestdocCount,
            'incoming_count' => $incomingCount,
            'received_count' => $receivedCount,
            'forwarded_count' => $forwardedCount,
            'deferred_count' => $deferredCount,
        ]);
    }

    
    public function createForward()
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $pageTitle = "Create & Forward New Document";
        $systemSetting =DtsSystemSetting::first();
         // Retrieve the aggregated count data for the user's section
         $mySection = NULL;
        $assignedSection=DtsSection::where('id', Auth::user()->section_id)->first();
        if ($assignedSection) {
            $mySection = $assignedSection->name;
        }
         $myAllSections= DB::table('section_user')->join('dts_sections', 'dts_sections.id','=','section_user.section_id')
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('name','asc')
                        ->get();
        $currentUserStationId = Auth::user()->section_id;           
            $sections = DtsSection::where('is_dropdown', true)->select('id', 'name')
                ->where('id', '!=', $currentUserStationId)
                ->where('id','>',1)//exclude the GUEST Section 1
                ->where('category_id', 1) // for division office
                ->where('is_dropdown', true)
                ->orderBy('name')
                ->get();
            // Retrieve all users
            $users = User::select('id', 'name')->get();

            // Retrieve all document types
            $docTypes = DtsDocType::orderBy('description', 'asc')->get();

    // Return the view with the retrieved data
    return view('dts.create-new-document', compact('sections', 'users', 'docTypes', 'pageTitle', 'mySection', 'myAllSections', 'systemSetting'));
    }

 
public function store(Request $request)
{
    abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    // Validate the request
    $request->validate([
        'description' => 'required',
        'actions_needed' => 'required',
        'dts_doc_type_id' => 'required',
        'tracking_issuedby_id' => 'required',
        'fromuser_id' => 'required',
        'from_section_id' => 'required',
        'to_section_id' => 'required',
        'to_user_id' => 'required',
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
        $document->fromuser_id = $request->input('fromuser_id');
        $document->from_section_id = $request->input('from_section_id');
        // $document->particulars = $request->input('particulars');
        $document->save();
        $fromUserId = Auth::user()->id;
        $fromSectionId = Auth::user()->section_id;
        // Insert forward route
        $routeId = $this->insertForwardRoute($document->id, $request->input('to_section_id'), $request->input('to_user_id'),$request->input('actions_needed'), $fromUserId, $fromSectionId);

        // Commit transaction
        DB::commit();

        // Redirect with success message
        return redirect()->route('dts.show-new-created', [$document->id, $routeId])->with('success', 'Document and Route created successfully.');
    } catch (\Exception $e) {
        // Rollback transaction on error
        DB::rollBack();
        return redirect()->back()->with('error', 'An error occurred while creating the document and route.');
    }
}

private function insertForwardRoute($documentId, $forSectionId, $forUserId, $actions_needed, $fromUserId, $fromSectionId ) //for first route
{
   
    $docRoute = new DtsDocRoute();
    $docRoute->dts_document_id = $documentId;
    $docRoute->for_section_id = $forSectionId;
    $docRoute->route_purpose = $actions_needed;
    $docRoute->for_user_id = $forUserId;
    $docRoute->from_user_id = $fromUserId;
    $docRoute->from_section_id = $fromSectionId;
    $docRoute->date_forwarded = date('Y-m-d H:i:s');
    $docRoute->status_id = 1;
    $docRoute->save();   
    return $docRoute->id;
}

public function getUsersBySection($sectionId)
{
    $users = User::where('section_id', $sectionId)->select('id', 'name')->orderBy('name', 'asc')->get();
    return response()->json($users);
}


    /**
     * Display the newly created document with a page displaying in bold or featured the document tracking.
     */
    public function showNewCreated($docId, $routeId)
    { 
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        $document = DtsDocument::findOrFail($docId);   
        $qrCodes['styleRound'] = QrCode::size(120)->style('round')->generate($document->tracking_code);    
        $systemSetting =DtsSystemSetting::first();
       $docTypes= DtsDocType::all();
        $route= DtsDocRoute::findOrFail($routeId);
        $docRoutes= DtsDocRoute:: with('fromUser','fromSection','forSection','receiverUser','actedByUser')
                    ->where('dts_document_id', $docId)
                    ->get();
      
        return view('dts.show-new-created', compact('document', 'route', 'systemSetting','routeId', 'mySection', 'myAllSections', 'assignedSection', 'qrCodes', 'docRoutes', 'docTypes'));
    }

   public function search(Request $request)
    {
        $request->validate([
            'search' => 'required',
        ]);
        $systemSetting =DtsSystemSetting::first();
        $search = $request->input('search');
        $tableTitle = "Search Results";
         // Store the search query in the session
         Session::put('search', $search);
         $mySection = NULL;
         $assignedSection=DtsSection::where('id', Auth::user()->section_id)->first();
         if ($assignedSection) {
             $mySection = $assignedSection->name;
         }
          $myAllSections= DB::table('section_user')->join('dts_sections', 'dts_sections.id','=','section_user.section_id')
                         ->where('user_id', Auth::user()->id)
                         ->orderBy('name','asc')
                         ->get();   
          $sections = DtsSection::orderBy('name', 'asc')->get(); 

        $documents = DtsDocument:: with('fromUser')->where('tracking_code', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%')
            ->orWhere('actions_needed', 'like', '%' . $search . '%')
            ->orWhereHas('docType', function ($query) use ($search) {
                $query->where('description', 'like', '%' . $search . '%');
            })
            //guest origin name
            ->orWhere('guest_origin_name', 'like', '%' . $search . '%')
            ->orWhere('created_at', 'like', '%' . $search . '%')
            ->orderBy('id', 'desc')
         //   ->get();
            ->paginate(100);
        return view('dts.searched-list', compact('documents', 'tableTitle', 'mySection', 'myAllSections', 'sections', 'systemSetting'));
    }

    public function searchResults(Request $request)
    {
        $search = Session::get('search');
        $tableTitle = "Search Results";
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

        $documents = DtsDocument::with('fromUser')
            ->where('tracking_code', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%')
            ->orWhere('actions_needed', 'like', '%' . $search . '%')
            ->orWhereHas('docType', function ($query) use ($search) {
                $query->where('description', 'like', '%' . $search . '%');
            })
            ->orWhere('guest_origin_name', 'like', '%' . $search . '%')
            ->orWhere('created_at', 'like', '%' . $search . '%')
            ->orderBy('id', 'desc')
            ->paginate(100);

        return view('dts.searched-list', compact('documents', 'tableTitle', 'mySection', 'myAllSections', 'sections', 'systemSetting'));
    }
   
   
    /**
     * Display the specified resource.
     */
    public function show(DtsDocument $dtsDocument)
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $docId=$dtsDocument->id;
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
        $docTypes= DtsDocType::all();
        $currentUserStationId = Auth::user()->section_id;    
        $document = DtsDocument::findOrFail($docId);
        $qrCodes['styleRound'] = QrCode::size(120)->style('round')->generate($document->tracking_code);
        $docRoutes= DtsDocRoute:: with('fromUser','fromSection','forSection','receiverUser','actedByUser')
                    ->where('dts_document_id', $docId)
                    ->get();
        $latestRoute = DtsDocRoute::where('dts_document_id', $docId)->orderBy('id', 'desc')->first();
    
        return view('dts.show-document', compact('document','docRoutes', 'mySection', 'myAllSections','assignedSection','qrCodes', 'systemSetting', 'docTypes', 'latestRoute'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DtsDocument $dtsDocument)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateNewDocument(Request $request)
{
    abort_if(Gate::denies('dts_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    // Validate the request
    $request->validate([
        'doc_id' => 'required|exists:dts_documents,id',
        'description' => 'required|string|max:255',
        'actions_needed' => 'required|string|max:255',
        'dts_doc_type_id' => 'required|exists:dts_doc_types,id',
    ]);
        $document = DtsDocument::findOrFail($request->input('doc_id'));
        $document->description = $request->input('description');
        $document->actions_needed = $request->input('actions_needed');
        $document->dts_doc_type_id = $request->input('dts_doc_type_id');
        $document->save();

        return redirect()->route('dts.show-new-created', [$document->id, $request->routeId])->with('success', 'Document edited successfully.');
    
}

public function updateDocument(Request $request)
{
    abort_if(Gate::denies('dts_document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    // Validate the request
    $request->validate([
        'doc_id' => 'required|exists:dts_documents,id',
        'description' => 'required|string|max:255',
        'actions_needed' => 'required|string|max:255',
        'dts_doc_type_id' => 'required|exists:dts_doc_types,id',
    ]);
        $document = DtsDocument::findOrFail($request->input('doc_id'));
        $document->description = $request->input('description');
        $document->actions_needed = $request->input('actions_needed');
        $document->dts_doc_type_id = $request->input('dts_doc_type_id');
        $document->save();
        //return back
        return redirect()->route('dts.document-view', $document->id)->with('success', 'Document edited successfully.');
    
}

   public function update(Request $request, DtsDocument $dtsDocument)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DtsDocument $dtsDocument)
    {
        //
    }
}
