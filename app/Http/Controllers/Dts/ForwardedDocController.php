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

class ForwardedDocController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tableTitle="Forwarded Documents to other sections";   
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
                       ->where('category_id', 1) // for division office
                       ->where('is_dropdown', true)
                       ->orderBy('name')
                       ->get();         
         $documents = DtsDocRoute::with(['document', 'fromSection', 'fromUser'])
            ->where('from_section_id', Auth::user()->section_id)
            ->whereNull('date_accepted')
            ->orderBy('id', 'desc')
           ->paginate(100);
       return view("dts.forwarded-docs", compact('tableTitle', 'documents', 'sections','mySection', 'myAllSections', 'systemSetting'));
    }


    
public function updateForwardedDoc(Request $request)
{
    // Validate the request
    $validatedData = $request->validate([
        'route_id' => 'required|exists:dts_doc_routes,id',
        'route_purpose' => 'required|string',
        'for_section_id' => 'required|exists:dts_sections,id',
        'for_user_id' => 'required|exists:users,id',
    ]);

    // Find the document route
    $docRoute = DtsDocRoute::findOrFail($validatedData['route_id']);

    // Update the document route with validated data
    $docRoute->update([
        'route_purpose' => $validatedData['route_purpose'],
        'for_section_id' => $validatedData['for_section_id'],
        'for_user_id' => $validatedData['for_user_id'],
    ]);

       return redirect()->route('dts.forwarded-docs.index')->with('success', 'Document route updated successfully.');
}

public function cancelForwardedDoc(Request $request)
    {
        $validatedData = $request->validate([
            'route_id' => 'required|exists:dts_doc_routes,id',
            'del_reason' => 'required|string',
            'prev_route_id' => 'nullable|exists:dts_doc_routes,id',
        ]);

        DB::beginTransaction();

        try {
            $docRoute = DtsDocRoute::findOrFail($validatedData['route_id']);
            $docRoute->update([
                'status_id' => 11,
                'del_reason' => $validatedData['del_reason'] . ' : Cancelled by ' . Auth::user()->name,
            ]);
            $docRoute->delete();

            if ($request->prev_route_id) {
                $prevDocRoute = DtsDocRoute::findOrFail($request->prev_route_id);
                $prevDocRoute->update([
                    'status_id' => 1,
                    'date_acted' => null,
                ]);
            }

            DB::commit();

            return redirect()->route('dts.forwarded-docs.index')->with('success', 'Document route cancelled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('dts.forwarded-docs.index')->with('error', 'Failed to cancel document route.');
        }
    }

    // GET ME HERE ALL THE TRASHED DOCUMENTS
    public function trashedDocs()
    {
        $tableTitle="Trashed Documents";   
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
            ->where('from_section_id', Auth::user()->section_id)
            ->whereNotNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();
       return view("dts.trashed-docs", compact('tableTitle', 'documents','mySection', 'myAllSections', 'systemSetting'));
    }

    //RESTORE TRASHED DOCUMENT if route_id is not duplicate with prev_route_id column
    public function restoreTrashedDoc(Request $request)
    {
        // Validate the request
        $request->validate([
            'route_id' => 'required|exists:dts_doc_routes,id',
               ]);
            // check if the route_id is not duplicate with prev_route_id column
             $hasDocRoute = DtsDocRoute::where('previous_route_id', $request->route_id)
                     ->whereNull('deleted_at')
                     ->exists();

                if ($hasDocRoute) {
                    return response()->json([
                        'message' => 'Document cannot be restored. It is a duplicate.',
                    ], 409);
                } else {
                    $docRoute = DtsDocRoute::withTrashed()->findOrFail($request->route_id);
                    $docRoute->restore();
                    return response()->json([
                        'message' => 'Document restored successfully.',
                        'doc_route' => $docRoute,
                    ]);
                }

          
            
             

    }
 
    
}
