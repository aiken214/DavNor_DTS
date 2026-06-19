<?php

namespace App\Http\Controllers\Dts;

use App\Http\Controllers\Controller;
use App\Models\DtsDocRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class DtsDocRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }



    
    public function forwardDocument(Request $request) {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $documentId = $request->input('dts_document_id');
        $newFromSectionId = $request->input('from_section_id');
        
        // Validate the request
        $data = $this->validateRequest($request);
        
             
        // Create new route within a transaction
        try {
            $newRoute = DB::transaction(function () use ($request) {
                // Update the previous route's status_id to 1
                DtsDocRoute::where('id', $request->input('previous_route_id'))
                        ->update([
                            'status_id' => 1,
                            'date_acted' => now(), // Adding the current timestamp
                            'end_remarks'=>'The Document is Forwarded',
                        ]);
        
                // Create the new route
                return DtsDocRoute::create([
                    'dts_document_id' => $request->input('dts_document_id'),
                    'previous_route_id' => $request->input('previous_route_id'), // Referencing the correct previous route
                    'from_user_id' => Auth::id(),
                    'fwd_io_type'=> $request->input('fwd_io_type'),
                    'from_section_id' => $request->input('from_section_id'),
                    'for_section_id' => $request->input('for_section_id'),
                    'for_user_id' => $request->input('for_user_id'),
                    'route_purpose' => $request->input('route_purpose')
                ]);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to forward document: ' . $e->getMessage());
            return redirect()->route('dts.received-docs')->with('error', 'Failed to forward document. Please try again.');
        }
        
        return redirect()->route('dts.received-docs')->with('success', 'Document is forwarded successfully');
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
