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




class IncomingDocController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $tableTitle = "Incoming Document (forwarded in the system but not yet received)";
        $systemSetting =DtsSystemSetting::first();
        $tableTitle="Guest Documents for receipt";
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
            ->whereNull('date_accepted')
            ->where('status_id', 1)
            ->orderBy('id', 'desc')
            ->paginate(1000);    
            
      return view("dts.incoming-docs", compact('tableTitle', 'mySection', 'documents', 'myAllSections', 'systemSetting'));
    }


    public function acceptDoc(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'doc_route_id' => 'required|numeric|exists:dts_doc_routes,id',
            'document_id' => 'required|numeric|exists:dts_documents,id',
            'accept_remarks' => 'nullable|string|max:255',
            'io_type' => 'required|integer',
        ]);
    
        try {
            // Find and update the document route
            $docRoute = DtsDocRoute::findOrFail($validated['doc_route_id']);
            $docRoute->dts_document_id = $validated['document_id'];
            $docRoute->accepting_remarks = $validated['accept_remarks'];
            $docRoute->io_type = $validated['io_type'];
            $docRoute->date_accepted = now();
            $docRoute->receiver_user_id = Auth::id();
            $docRoute->status_id = 2; // 2 for received
            $docRoute->save();
    
    
            return redirect()->route('dts.incoming-docs.index')->with('success', 'Document accepted successfully');
    
        } catch (\Exception $e) {
            // Temporarily use dd to inspect the exception
         //   dd($e);
            Log::error($e->getMessage());
            return redirect()->route('dts.incoming-docs.index')->with('error', 'An error occurred while accepting the document');
        }
    }
    
    
    public function acceptAndFileDoc(Request $request)
    {

         // Validate the request data
         $validated = $request->validate([
            'doc_route_id' => 'required|numeric|exists:dts_doc_routes,id',
            'document_id' => 'required|numeric|exists:dts_documents,id',
            'accept_remarks' => 'nullable|string|max:255',
            'io_type' => 'required|integer',
        ]);
    
        try {
            // Find and update the document route
            $docRoute = DtsDocRoute::findOrFail($validated['doc_route_id']);
            $docRoute->dts_document_id = $validated['document_id'];
            $docRoute->accepting_remarks = $validated['accept_remarks'];
            $docRoute->date_accepted = now();
            $docRoute->receiver_user_id = Auth::id();
            $docRoute->actions_taken = 'Filed/Kept';
            $docRoute->io_type = $validated['io_type'];
            $docRoute->status_id = 3; // 3 for filed
            $docRoute->actedby_user_id = Auth::id();
            $docRoute->date_acted= now();
            $docRoute->save();

            return redirect()->route('dts.incoming-docs.index')->with('success', 'Document accepted successfully');
    
        } catch (\Exception $e) {
            // Temporarily use dd to inspect the exception
         //   dd($e);
            Log::error($e->getMessage());
            return redirect()->route('dts.incoming-docs.index')->with('error', 'An error occurred while accepting the document');
        }
    }
   
   
    
}
