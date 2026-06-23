<?php

namespace App\Http\Controllers\Dts;

use App\Http\Controllers\Controller;
use App\Models\DtsBatchRelease;
use App\Models\DtsDocRoute;
use App\Models\DtsPigeonhole;
use App\Models\DtsSection;
use App\Models\DtsSystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;


class BatchReleaseController extends Controller
{
    
    public function index()
    {
        abort_if(Gate::denies('dts_batch_release_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $tableTitle="Deferred Document, needs action";  
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
        $sections = DtsSection::select('id', 'name')
                        ->where('id', '!=', Auth::user()->section_id)
                        ->where('id','>',1) // Exclude the first section for GUEST
                        ->where('category_id', 1) // for division office
                        ->where('is_dropdown', true)
                        ->orderBy('name')
                        ->get();
        $batchReleases = DtsBatchRelease::with('createdBy', 'releasedBy')
                        ->orderByRaw('releaseby_id IS NULL DESC')
                        ->orderBy('id', 'desc')
                         ->where('section_id', Auth::user()->section_id)
                         ->limit(500)
                         ->get();

        return view('dts.batch-release-list', compact('batchReleases', 'sections', 'mySection', 'myAllSections', 'tableTitle', 'systemSetting'));
    }

    public function releaseDocs(Request $request){
        abort_if(Gate::denies('dts_batch_release_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $batch = DtsBatchRelease::findOrFail($request->input('batch_release_id'));
        $batch->release_date = date('Y-m-d H:i:s');
        $batch->releaseby_id = Auth::user()->id;
        $batch->receiver_name = $request->input('receiver_name');
        $batch->save();
         return redirect()->back()->with('success', 'Batch created successfully.');            

    }

    public function show($batchReleaseId)
    {
        abort_if(Gate::denies('dts_batch_release_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
        $batchRelease = DtsBatchRelease::with('createdBy', 'releasedBy')
                        ->findOrFail($batchReleaseId);
        $forBatchRelease = DB::table('dts_batch_release_doc_route')
                         ->join('dts_doc_routes', 'dts_doc_routes.id', '=', 'dts_batch_release_doc_route.doc_route_id')
                         ->join('dts_documents', 'dts_documents.id', '=', 'dts_doc_routes.dts_document_id')
                        ->join('dts_doc_types', 'dts_doc_types.id', '=', 'dts_documents.dts_doc_type_id')
                        ->select('dts_batch_release_doc_route.*','dts_documents.id as doc_id', 'dts_documents.tracking_code' ,'dts_documents.description', 'dts_doc_types.description as type_description', 'dts_doc_routes.id as route_id')
                        ->where('dts_batch_release_doc_route.batch_release_id', $batchReleaseId)
                        ->get();
        $receivedDocuments = DtsDocRoute::with('document', 'docType')
                        ->where('for_section_id', Auth::user()->section_id)
                        ->where(function ($query) {
                            $query->where('status_id', 2) // received
                                  ->orWhere('status_id', 5); // deferred
                        })
                        ->whereNotIn('id', function ($query) {
                            $query->select('doc_route_id')
                                  ->from('dts_batch_release_doc_route');
                        })
                        ->get();
   
        $pigeonholes = DtsPigeonhole::with('section')->where('is_active', true)->orderBy('name')->get();

        return view('dts.batch-release-show', compact('systemSetting', 'mySection', 'myAllSections','batchRelease', 'forBatchRelease', 'receivedDocuments', 'pigeonholes'));
    }

    public function pigeonholeDocs($pigeonholeId)
    {
        abort_if(Gate::denies('dts_batch_release_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docs = DtsDocRoute::with(['document'])
            ->where('pigeonhole_id', $pigeonholeId)
            ->where('status_id', 1)
            ->whereNotIn('id', function ($query) {
                $query->select('doc_route_id')->from('dts_batch_release_doc_route');
            })
            ->get()
            ->map(function ($doc) {
                return [
                    'route_id' => $doc->id,
                    'tracking_code' => $doc->document->tracking_code,
                    'description' => $doc->document->description,
                ];
            });

        return response()->json($docs);
    }

    public function forPrintView($batchReleaseId)
    {
        abort_if(Gate::denies('dts_batch_release_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
        $batchRelease = DtsBatchRelease::with('createdBy', 'releasedBy')
                        ->findOrFail($batchReleaseId);
        $forBatchReleasesDocuments = DB::table('dts_batch_release_doc_route')
                         ->join('dts_doc_routes', 'dts_doc_routes.id', '=', 'dts_batch_release_doc_route.doc_route_id')
                         ->join('dts_documents', 'dts_documents.id', '=', 'dts_doc_routes.dts_document_id')
                        ->join('dts_doc_types', 'dts_doc_types.id', '=', 'dts_documents.dts_doc_type_id')
                        ->select('dts_documents.id as doc_id', 'dts_documents.tracking_code' ,'dts_documents.description', 'dts_doc_types.description as type_description', 'dts_doc_routes.id as route_id')
                        ->where('dts_batch_release_doc_route.batch_release_id', $batchReleaseId)
                        ->get();
       return view('dts.batch-release-print', compact('mySection', 'myAllSections','batchRelease', 'forBatchReleasesDocuments', 'systemSetting'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('dts_batch_release_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
        ]);
        $batchRelease = DtsBatchRelease::findOrFail($id);
        if ($batchRelease->releaseby_id !== null) {
            return redirect()->back()->with('error', 'Cannot edit a released batch.');
        }
        $batchRelease->update($request->only(['name', 'description']));
        return redirect()->back()->with('success', 'Batch updated successfully.');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('dts_batch_release_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // Validate the request
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'createdby_id' => 'required|exists:users,id',
            'section_id' => 'required|exists:dts_sections,id',
            'release_date' => 'nullable|date',
            'receiver_name' => 'nullable|string',
        ]);

        // Create the batch release
        $batchRelease = DtsBatchRelease::create($request->only(['name', 'description', 'createdby_id', 'section_id', 'release_date', 'receiver_name']));

        // return response()->json([
        //     'message' => 'Batch release created successfully.',
        //     'batch_release' => $batchRelease,
        // ]);

        return redirect()->back()->with('success', 'Batch created successfully.');
    }
    public function updateDocRoutes(Request $request, $batchReleaseId)
    {        
        abort_if(Gate::denies('dts_batch_release_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // Validate the request
        $request->validate([
            'doc_route_ids' => 'required|array',
            'doc_route_ids.*' => 'exists:dts_doc_routes,id',
        ]);

        // Find the batch release
        $batchRelease = DtsBatchRelease::findOrFail($batchReleaseId);

        // Sync the doc routes
        $batchRelease->docRoutes()->sync($request->input('doc_route_ids'));

        return response()->json([
            'message' => 'Doc routes updated successfully.',
            'batch_release' => $batchRelease->load('docRoutes'),
        ]);
    }

    public function releaseBatchFinalize(Request $request){
        abort_if(Gate::denies('dts_batch_release_finalize'), Response::HTTP_FORBIDDEN, '403 Forbidden'); 
        // Validate the request
        $request->validate([
            'batch_release_id' => 'required|exists:dts_batch_releases,id',
            'receiver_name' => 'required|string',
        ]);
        // Find the batch release
        $batchRelease = DtsBatchRelease::findOrFail($request->input('batch_release_id'));
        // Update the batch release status
        $batchRelease->update(['release_date' => now(),
                                'releaseby_id' => Auth::user()->id,
                                'receiver_name' => $request->input('receiver_name')
                            ]);  
     return redirect()->route('dts.batch-releases.index')->with('success', 'Batch ir finalize and released successfully');
    }




public function addOneItemforRelease(Request $request){

        abort_if(Gate::denies('dts_batch_release_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // Validate the request
        $request->validate([
            'doc_route_id' => 'required|exists:dts_doc_routes,id',
            'batch_release_id' => 'required|exists:dts_batch_releases,id',
        ]);
         // Use a transaction to ensure all operations succeed or fail together
    DB::transaction(function () use ($request) {
        // update  route status to 11 (for batch release)
        $docRoute = DtsDocRoute::findOrFail($request->input('doc_route_id'));
        $docRoute->update(['status_id' => 11]);
        // user insert to pivot table using query builder
            DB::table('dts_batch_release_doc_route')->insert([
            'batch_release_id' => $request->input('batch_release_id'),
            'doc_route_id' => $request->input('doc_route_id'),
            'created_at' => now(),
        ]);
    });
      
    return redirect()->back()->with('success', 'Document is included successfully.');

}

public function removeOneItemforRelease(Request $request){
        abort_if(Gate::denies('dts_batch_release_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // Validate the request
        $request->validate([
            'id' => 'required|exists:dts_batch_release_doc_route,id',
            'doc_route_id' => 'required|exists:dts_doc_routes,id',
        ]);
        DB::transaction(function () use ($request) {
        // update  route status to 2 (received)
        $docRoute = DtsDocRoute::findOrFail($request->input('doc_route_id'));
        $docRoute->update(['status_id' => 2]);
        // user insert to pivot table using query builder
        DB::table('dts_batch_release_doc_route')
            ->where('id', $request->input('id'))
            ->delete();
        });
      
    return redirect()->back()->with('success', 'Document is removed successfully.');
}



  public function addItemsForRelease(Request $request){            
        abort_if(Gate::denies('dts_batch_release_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // Validate the request
        $request->validate([
            'doc_route_ids' => 'required|array',
            'doc_route_ids.*' => 'exists:dts_doc_routes,id',
            'batch_release_id' => 'required|exists:dts_batch_releases,id',
        ]);

        // Find the batch release
        $batchRelease = DtsBatchRelease::findOrFail($request->input('batch_release_id'));

        // Attach the doc routes
        $batchRelease->docRoutes()->attach($request->input('doc_route_ids'));

        return response()->json([
            'message' => 'Items added to batch release successfully.',
            'batch_release' => $batchRelease->load('docRoutes'),
        ]);
    }

    public function removeItemsForRelease(Request $request){
        abort_if(Gate::denies('dts_batch_release_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // Validate the request
        $request->validate([
            'doc_route_ids' => 'required|array',
            'doc_route_ids.*' => 'exists:dts_doc_routes,id',
            'batch_release_id' => 'required|exists:dts_batch_releases,id',
        ]);

        // Find the batch release
        $batchRelease = DtsBatchRelease::findOrFail($request->input('batch_release_id'));

        // Detach the doc routes
        $batchRelease->docRoutes()->detach($request->input('doc_route_ids'));

        return response()->json([
            'message' => 'Items removed from batch release successfully.',
            'batch_release' => $batchRelease->load('docRoutes'),
        ]);

  }


}
