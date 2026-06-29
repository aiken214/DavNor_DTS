<?php

namespace App\Http\Controllers\Dts;

use App\Http\Controllers\Controller;
use App\Models\DtsBatchSubmit;
use App\Models\DtsDocRoute;
use App\Models\DtsDocument;
use App\Models\DtsSection;
use App\Models\DtsSystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class BatchReceivedController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('dts_batch_received_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $systemSetting = DtsSystemSetting::first();
        $mySection = null;
        $assignedSection = DtsSection::where('id', Auth::user()->section_id)->first();
        if ($assignedSection) {
            $mySection = $assignedSection->name;
        }
        $myAllSections = DB::table('section_user')
            ->join('dts_sections', 'dts_sections.id', '=', 'section_user.section_id')
            ->where('user_id', Auth::user()->id)
            ->orderBy('name', 'asc')
            ->get();

        $batches = DtsBatchSubmit::with('createdBy', 'submittedBy', 'section', 'forSection')
            ->whereNotNull('submittedby_id')
            ->orderBy('submit_date', 'desc')
            ->limit(500)
            ->get();

        return view('dts.batch-received-list', compact('batches', 'mySection', 'myAllSections', 'systemSetting'));
    }

    public function show($batchSubmitId)
    {
        abort_if(Gate::denies('dts_batch_received_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $systemSetting = DtsSystemSetting::first();
        $mySection = null;
        $assignedSection = DtsSection::where('id', Auth::user()->section_id)->first();
        if ($assignedSection) {
            $mySection = $assignedSection->name;
        }
        $myAllSections = DB::table('section_user')
            ->join('dts_sections', 'dts_sections.id', '=', 'section_user.section_id')
            ->where('user_id', Auth::user()->id)
            ->orderBy('name', 'asc')
            ->get();

        $batchSubmit = DtsBatchSubmit::with('createdBy', 'submittedBy', 'section', 'forSection')->findOrFail($batchSubmitId);

        $batchDocuments = DB::table('dts_batch_submit_doc_route')
            ->join('dts_doc_routes', 'dts_doc_routes.id', '=', 'dts_batch_submit_doc_route.doc_route_id')
            ->join('dts_documents', 'dts_documents.id', '=', 'dts_batch_submit_doc_route.dts_document_id')
            ->join('dts_doc_types', 'dts_doc_types.id', '=', 'dts_documents.dts_doc_type_id')
            ->select(
                'dts_batch_submit_doc_route.id',
                'dts_batch_submit_doc_route.doc_route_id',
                'dts_documents.id as doc_id',
                'dts_documents.tracking_code',
                'dts_documents.description',
                'dts_documents.actions_needed',
                'dts_doc_types.description as type_description',
                'dts_doc_routes.status_id'
            )
            ->where('dts_batch_submit_doc_route.batch_submit_id', $batchSubmitId)
            ->whereNull('dts_doc_routes.deleted_at')
            ->get();

        $sections = DtsSection::orderBy('name')->get();

        return view('dts.batch-received-show', compact(
            'systemSetting', 'mySection', 'myAllSections', 'batchSubmit', 'batchDocuments', 'sections'
        ));
    }

    public function deleteDocument(Request $request)
    {
        abort_if(Gate::denies('dts_batch_received_manage'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'id' => 'required',
            'doc_route_id' => 'required',
            'dts_document_id' => 'required',
            'delete_reason' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                DtsDocRoute::where('id', $request->input('doc_route_id'))->delete();
                DtsDocument::where('id', $request->input('dts_document_id'))->delete();
                DB::table('dts_batch_submit_doc_route')
                    ->where('id', $request->input('id'))
                    ->delete();
            });

            return redirect()->back()->with('success', 'Document entry deleted. Reason: ' . $request->input('delete_reason'));
        } catch (\Exception $e) {
            Log::error('Batch received delete document failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete document.');
        }
    }

    public function rerouteDocument(Request $request)
    {
        abort_if(Gate::denies('dts_batch_received_manage'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'doc_route_id' => 'required',
            'dts_document_id' => 'required',
            'reroute_section_id' => 'required|exists:dts_sections,id',
            'reroute_reason' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $oldRoute = DtsDocRoute::findOrFail($request->input('doc_route_id'));

                // Accept the current route
                $oldRoute->receiver_user_id = Auth::id();
                $oldRoute->date_accepted = now();
                $oldRoute->status_id = 6; // forwarded
                $oldRoute->date_acted = now();
                $oldRoute->end_remarks = 'Re-routed: ' . $request->input('reroute_reason');
                $oldRoute->save();

                // Create new route to the selected section
                $newRoute = new DtsDocRoute();
                $newRoute->dts_document_id = $request->input('dts_document_id');
                $newRoute->previous_route_id = $oldRoute->id;
                $newRoute->from_user_id = Auth::id();
                $newRoute->from_section_id = Auth::user()->section_id;
                $newRoute->for_section_id = $request->input('reroute_section_id');
                $newRoute->route_purpose = 'Re-routed: ' . $request->input('reroute_reason');
                $newRoute->date_forwarded = now();
                $newRoute->status_id = 1;
                $newRoute->save();
            });

            return redirect()->back()->with('success', 'Document re-routed successfully.');
        } catch (\Exception $e) {
            Log::error('Batch received reroute failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to re-route document.');
        }
    }

    public function forwardBatch(Request $request)
    {
        abort_if(Gate::denies('dts_batch_received_manage'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'batch_submit_id' => 'required|exists:dts_batch_submits,id',
        ]);

        $batch = DtsBatchSubmit::findOrFail($request->input('batch_submit_id'));

        if (!$batch->for_section_id) {
            return redirect()->back()->with('error', 'No destination section set for this batch.');
        }

        $docRouteIds = DB::table('dts_batch_submit_doc_route')
            ->where('batch_submit_id', $batch->id)
            ->pluck('doc_route_id');

        $pendingRoutes = DtsDocRoute::whereIn('id', $docRouteIds)
            ->where('status_id', 1)
            ->get();

        if ($pendingRoutes->isEmpty()) {
            return redirect()->back()->with('error', 'No pending documents to forward.');
        }

        try {
            DB::transaction(function () use ($pendingRoutes, $batch) {
                foreach ($pendingRoutes as $oldRoute) {
                    $oldRoute->receiver_user_id = Auth::id();
                    $oldRoute->date_accepted = now();
                    $oldRoute->status_id = 6;
                    $oldRoute->date_acted = now();
                    $oldRoute->end_remarks = 'Forwarded via batch to ' . ($batch->forSection->name ?? 'destination');
                    $oldRoute->save();

                    $newRoute = new DtsDocRoute();
                    $newRoute->dts_document_id = $oldRoute->dts_document_id;
                    $newRoute->previous_route_id = $oldRoute->id;
                    $newRoute->from_user_id = Auth::id();
                    $newRoute->from_section_id = Auth::user()->section_id;
                    $newRoute->for_section_id = $batch->for_section_id;
                    $newRoute->route_purpose = 'Via Batch Submit: ' . $batch->batch_code;
                    $newRoute->date_forwarded = now();
                    $newRoute->status_id = 1;
                    $newRoute->save();
                }
            });

            return redirect()->back()->with('success', $pendingRoutes->count() . ' document(s) forwarded to ' . ($batch->forSection->name ?? 'destination section') . '.');
        } catch (\Exception $e) {
            Log::error('Batch forward failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to forward batch.');
        }
    }
}
