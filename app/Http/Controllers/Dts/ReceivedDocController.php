<?php

namespace App\Http\Controllers\Dts;

use App\Http\Controllers\Controller;
use App\Models\DtsDocRoute;
use App\Models\DtsPigeonhole;
use App\Models\DtsSection;
use App\Models\DtsSystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class ReceivedDocController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $systemSetting =DtsSystemSetting::first();
        $tableTitle="Received Document needs action";      
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
            $sections = DtsSection::select('id', 'name')
                           ->where('id', '!=', $currentUserStationId)
                           ->where('id','>',1) // Exclude the first section for GUEST
                           ->where('category_id', 1) // for division office
                           ->where('is_dropdown', true)
                           ->orderBy('name')
                           ->get();
        $documents = DtsDocRoute::with(['document', 'fromSection', 'fromUser'])
        ->where('for_section_id', Auth::user()->section_id)
        ->where('status_id', 2)
        ->paginate(1000);

        $pigeonholes = DtsPigeonhole::with('section')->where('is_active', true)->orderBy('name')->get();

        return view("dts.received-docs", compact('tableTitle','documents', 'sections','mySection', 'myAllSections','assignedSection', 'systemSetting', 'pigeonholes'));

    }

    public function fileKept(Request $request)
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validated = $request->validate([
            'dts_document_id' => 'required|integer',
            'route_id' => 'required|integer',            
        ]);
        $routeId = $request->input('route_id');
        $remarks = $request->input('remarks');
        $route = DtsDocRoute::findOrFail($routeId);
        $route->status_id = 3;
        $route->date_acted = now();
        $route->end_remarks = $remarks;
        $route->save();
       return redirect()->back()->with('success', 'Document is filed successfully.');
    }

    public function fileReleased(Request $request)
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validated = $request->validate([
            'dts_document_id' => 'required|integer',
            'route_id' => 'required|integer',
            'release_to' => 'required|string|max:255',
        ]);
        $routeId = $request->input('route_id');
        $remarks = $request->input('remarks');
        $route = DtsDocRoute::findOrFail($routeId);
        $route->status_id = 4;
        $route->date_acted = now();
        $route->out_released_to=$request->input('release_to');
        $route->end_remarks = 'The Document is Released to '.$request->input('release_to').' '. $remarks;
        $route->save();
       return redirect()->back()->with('success', 'Doc Released is recorded successfully.');
    }

    public function deferredDoc(Request $request)
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validated = $request->validate([
            'dts_document_id' => 'required|integer',
            'route_id' => 'required|integer',
            'deferred_reason' => 'required|string',
            'defer_until' => 'required|date|after:now',
        ]);
        $routeId = $request->input('route_id');
        $deferred_reason = $request->input('deferred_reason');
        $route = DtsDocRoute::findOrFail($routeId);
        $route->status_id = 5;
        $route->deferred_date = now();
        $route->defer_until = $request->input('defer_until');
        $route->deferred_reason = $deferred_reason;
        $route->end_remarks = 'The Document is Deferred';
        $route->save();
        return redirect()->route('dts.received-docs.index')->with('success', 'Document is deferred successfully');
    }

    public function sendToPigeonhole(Request $request)
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validated = $request->validate([
            'dts_document_id' => 'required|integer',
            'route_id' => 'required|integer',
            'pigeonhole_id' => 'required|numeric|exists:dts_pigeonholes,id',
            'remarks' => 'nullable|string|max:255',
        ]);

        try {
            $pigeonhole = DtsPigeonhole::findOrFail($validated['pigeonhole_id']);

            DB::transaction(function () use ($validated, $pigeonhole, $request) {
                $route = DtsDocRoute::findOrFail($validated['route_id']);
                $route->status_id = 6;
                $route->date_acted = now();
                $route->actedby_user_id = Auth::id();
                $route->end_remarks = 'Sent to Pigeonhole: ' . $pigeonhole->name;
                $route->save();

                DtsDocRoute::create([
                    'dts_document_id' => $validated['dts_document_id'],
                    'previous_route_id' => $validated['route_id'],
                    'from_user_id' => Auth::id(),
                    'from_section_id' => Auth::user()->section_id,
                    'for_section_id' => $pigeonhole->section_id,
                    'for_user_id' => $pigeonhole->section->default_user_id,
                    'date_forwarded' => now(),
                    'status_id' => 1,
                    'io_type' => 1,
                    'route_purpose' => 'Via Pigeonhole: ' . $pigeonhole->name . ($request->remarks ? ' | ' . $request->remarks : ''),
                    'pigeonhole_id' => $pigeonhole->id,
                ]);
            });

            return redirect()->route('dts.received-docs.index')
                ->with('success', 'Document sent to Pigeonhole: ' . $pigeonhole->name . ' (' . $pigeonhole->section->name . ')');

        } catch (\Exception $e) {
            Log::error('Failed to send to pigeonhole: ' . $e->getMessage());
            return redirect()->route('dts.received-docs.index')
                ->with('error', 'An error occurred while sending the document to pigeonhole.');
        }
    }

    public function forwardDoc(Request $request) {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $documentId = $request->input('dts_document_id');
        $newFromSectionId = $request->input('from_section_id');
        
        // Validate the request
        $data = $this->validateRequest($request);
        if (!$request->has('route_purpose')) {
            return redirect()->route('dts.received-docs.index')->with('error', 'Route purpose is required.');
        }
               
        // Create new route within a transaction
        try {
            $newRoute = DB::transaction(function () use ($request) {
                // Update the previous route's status_id to 1
                DtsDocRoute::where('id', $request->input('previous_route_id'))
                        ->update([
                            'status_id' => 6,
                            'date_acted' => now(), // Adding the current timestamp
                            'end_remarks'=>'The Document is Forwarded',
                        ]);
        
                // Create the new route
                return DtsDocRoute::create([
                    'dts_document_id' => $request->input('dts_document_id'),
                    'previous_route_id' => $request->input('previous_route_id'), // Referencing the correct previous route
                    'from_user_id' => Auth::id(),
                    'date_forwarded' => now(), // Adding the current timestamp
                    'fwd_io_type'=> $request->input('fwd_io_type'),
                    'from_section_id' => $request->input('from_section_id'),
                    'for_section_id' => $request->input('for_section_id'),
                    'for_user_id' => $request->input('for_user_id'),
                    'status_id' => 1,
                    'route_purpose' => $request->input('route_purpose')
                ]);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to forward document: ' . $e->getMessage());
            return redirect()->route('dts.received-docs.index')->with('error', 'Failed to forward document. Please try again.');
        }
        
        return redirect()->route('dts.received-docs.index')->with('success', 'Document is forwarded successfully');
    }
    
    
    // Reusable validation method
    private function validateRequest(Request $request)
    {
        return $request->validate([
            'dts_document_id' => 'required|integer',
            'previous_route_id' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = DtsDocRoute::where('dts_document_id', $request->input('dts_document_id'))
                        ->where('previous_route_id', $value)
                        ->whereNull('deleted_at')
                        ->exists();
                    if ($exists) {
                        $fail('The previous route has already been made for this document.');
                    }
                }
            ],
            'from_section_id' => 'required|integer',
            'for_section_id' => 'required|integer',
            'for_user_id' => 'required|integer',
            'route_purpose' => 'required|string',
            'fwd_io_type' => 'required|string', // Adding validation for fwd_io_type
        ]);
    }
    
    // Check if the last route is from the same section
    private function isLastRouteFromSameSection($documentId, $newFromSectionId)
    {
        $lastRoute = DtsDocRoute::where('dts_document_id', $documentId)
                                ->orderBy('created_at', 'desc')
                                ->whereNull('deleted_at')
                                ->first();
        return $lastRoute && $lastRoute->for_section_id == $newFromSectionId;
    }
    
    // Get last route ID
    private function getLastRouteId($documentId)
    {
        $lastRoute = DtsDocRoute::where('dts_document_id', $documentId)
                                ->orderBy('created_at', 'desc')
                                ->whereNull('deleted_at')
                                ->first();
        return $lastRoute ? $lastRoute->id : null;
    }
    
}
