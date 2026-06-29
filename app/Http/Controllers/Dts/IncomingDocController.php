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
use App\Models\DtsPigeonhole;
use Carbon\Carbon;




class IncomingDocController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('dts_incoming_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $tableTitle = "Incoming Document (forwarded in the system but not yet received)";
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
            ->where('for_section_id', Auth::user()->section_id)
            ->whereNull('date_accepted')
            ->whereNull('pigeonhole_id')
            ->where('status_id', 1)
            ->orderBy('id', 'desc')
            ->paginate(1000);

        $pigeonholes = DtsPigeonhole::with('section')->where('is_active', true)->orderBy('name')->get();

        $forwardSections = DtsSection::where('id', '!=', Auth::user()->section_id)
            ->where('id', '>', 1)
            ->orderBy('name')
            ->get();

      return view("dts.incoming-docs", compact('tableTitle', 'mySection', 'documents', 'myAllSections', 'systemSetting', 'pigeonholes', 'forwardSections'));
    }


    public function acceptDoc(Request $request)
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
    
    
    public function sendToPigeonhole(Request $request)
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validated = $request->validate([
            'doc_route_id' => 'required|numeric|exists:dts_doc_routes,id',
            'document_id' => 'required|numeric|exists:dts_documents,id',
            'pigeonhole_id' => 'required|numeric|exists:dts_pigeonholes,id',
            'remarks' => 'nullable|string|max:255',
        ]);

        try {
            $pigeonhole = DtsPigeonhole::findOrFail($validated['pigeonhole_id']);

            DB::transaction(function () use ($validated, $pigeonhole, $request) {
                $docRoute = DtsDocRoute::findOrFail($validated['doc_route_id']);
                $docRoute->date_accepted = now();
                $docRoute->receiver_user_id = Auth::id();
                $docRoute->accepting_remarks = 'Received and sent to Pigeonhole: ' . $pigeonhole->name;
                $docRoute->io_type = 1;
                $docRoute->status_id = 6;
                $docRoute->date_acted = now();
                $docRoute->actedby_user_id = Auth::id();
                $docRoute->end_remarks = 'Sent to Pigeonhole: ' . $pigeonhole->name;
                $docRoute->save();

                DtsDocRoute::create([
                    'dts_document_id' => $validated['document_id'],
                    'previous_route_id' => $validated['doc_route_id'],
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

            return redirect()->route('dts.incoming-docs.index')
                ->with('success', 'Document sent to Pigeonhole: ' . $pigeonhole->name . ' (' . $pigeonhole->section->name . ') by ' . Auth::user()->name);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('dts.incoming-docs.index')
                ->with('error', 'An error occurred while sending the document to pigeonhole.');
        }
    }

    public function acceptAndFileDoc(Request $request)
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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

    public function forwardDoc(Request $request)
    {
        $validated = $request->validate([
            'doc_route_id' => 'required',
            'document_id' => 'required',
            'forward_section_id' => 'required|exists:dts_sections,id',
            'forward_remarks' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $docRoute = DtsDocRoute::findOrFail($validated['doc_route_id']);
            $docRoute->date_accepted = now();
            $docRoute->receiver_user_id = Auth::id();
            $docRoute->status_id = 6;
            $docRoute->date_acted = now();
            $docRoute->end_remarks = 'Forwarded to ' . DtsSection::find($validated['forward_section_id'])->name . ($validated['forward_remarks'] ? ' | ' . $validated['forward_remarks'] : '');
            $docRoute->save();

            $newRoute = new DtsDocRoute();
            $newRoute->dts_document_id = $validated['document_id'];
            $newRoute->previous_route_id = $docRoute->id;
            $newRoute->from_user_id = Auth::id();
            $newRoute->from_section_id = Auth::user()->section_id;
            $newRoute->for_section_id = $validated['forward_section_id'];
            $newRoute->route_purpose = $validated['forward_remarks'] ?? $docRoute->route_purpose;
            $newRoute->date_forwarded = now();
            $newRoute->status_id = 1;
            $newRoute->save();

            DB::commit();

            return redirect()->route('dts.incoming-docs.index')->with('success', 'Document forwarded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->route('dts.incoming-docs.index')->with('error', 'An error occurred while forwarding the document.');
        }
    }
}
