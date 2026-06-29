<?php

namespace App\Http\Controllers\Dts;

use App\Http\Controllers\Controller;
use App\Models\DtsBatchSubmit;
use App\Models\DtsDocRoute;
use App\Models\DtsDocType;
use App\Models\DtsDocument;
use App\Models\DtsSection;
use App\Models\DtsSystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class BatchSubmitController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('dts_batch_submit_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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

        $batchSubmits = DtsBatchSubmit::with('createdBy', 'submittedBy')
            ->where('createdby_id', Auth::id())
            ->orderByRaw('submittedby_id IS NULL DESC')
            ->orderBy('id', 'desc')
            ->limit(500)
            ->get();

        return view('dts.batch-submit-list', compact('batchSubmits', 'mySection', 'myAllSections', 'systemSetting'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('dts_batch_submit_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DtsBatchSubmit::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'createdby_id' => Auth::id(),
            'section_id' => Auth::user()->section_id,
        ]);

        return redirect()->back()->with('success', 'Batch created successfully.');
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('dts_batch_submit_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $batch = DtsBatchSubmit::findOrFail($id);
        if ($batch->submittedby_id !== null) {
            return redirect()->back()->with('error', 'Cannot edit a submitted batch.');
        }

        $batch->update($request->only(['name', 'description']));
        return redirect()->back()->with('success', 'Batch updated successfully.');
    }

    public function show($batchSubmitId)
    {
        abort_if(Gate::denies('dts_batch_submit_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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

        $batchSubmit = DtsBatchSubmit::with('createdBy', 'submittedBy')->findOrFail($batchSubmitId);

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
                'dts_doc_types.description as type_description'
            )
            ->where('dts_batch_submit_doc_route.batch_submit_id', $batchSubmitId)
            ->get();

        $docTypes = DtsDocType::orderBy('description', 'asc')->get();

        return view('dts.batch-submit-show', compact(
            'systemSetting', 'mySection', 'myAllSections', 'batchSubmit', 'batchDocuments', 'docTypes'
        ));
    }

    public function addDocument(Request $request)
    {
        abort_if(Gate::denies('dts_batch_submit_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'batch_submit_id' => 'required|exists:dts_batch_submits,id',
            'dts_doc_type_id' => 'required|exists:dts_doc_types,id',
            'description' => 'required|string',
            'actions_needed' => 'required|string',
        ]);

        $batch = DtsBatchSubmit::findOrFail($request->input('batch_submit_id'));
        if ($batch->submittedby_id !== null) {
            return redirect()->back()->with('error', 'Cannot add documents to a submitted batch.');
        }

        try {
            DB::transaction(function () use ($request, $batch) {
                $trackingInfo = DtsDocument::generateTrackingCode();

                $document = new DtsDocument();
                $document->tracking_code = $trackingInfo['tracking_code'];
                $document->mo_yr = $trackingInfo['mo_yr'];
                $document->issued_num = $trackingInfo['issued_num'];
                $document->description = $request->input('description');
                $document->actions_needed = $request->input('actions_needed');
                $document->dts_doc_type_id = $request->input('dts_doc_type_id');
                $document->tracking_issuedby_id = Auth::id();
                $document->fromuser_id = Auth::id();
                $document->from_section_id = Auth::user()->section_id;
                $document->save();

                $recordsSection = DtsSection::where('is_record_management', 1)
                    ->where('id', '!=', Auth::user()->section_id)
                    ->first();

                $docRoute = new DtsDocRoute();
                $docRoute->dts_document_id = $document->id;
                $docRoute->from_user_id = Auth::id();
                $docRoute->from_section_id = Auth::user()->section_id;
                $docRoute->for_section_id = $recordsSection->id;
                $docRoute->route_purpose = $request->input('actions_needed');
                $docRoute->date_forwarded = now();
                $docRoute->status_id = 1;
                $docRoute->save();

                DB::table('dts_batch_submit_doc_route')->insert([
                    'batch_submit_id' => $batch->id,
                    'doc_route_id' => $docRoute->id,
                    'dts_document_id' => $document->id,
                    'created_at' => now(),
                ]);
            });

            return redirect()->route('dts.batch-submits.show', $batch->id)->with('success', 'Document added successfully.');
        } catch (\Exception $e) {
            Log::error('Batch submit add document failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add document.');
        }
    }

    public function removeDocument(Request $request)
    {
        abort_if(Gate::denies('dts_batch_submit_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'id' => 'required|exists:dts_batch_submit_doc_route,id',
            'doc_route_id' => 'required|exists:dts_doc_routes,id',
            'dts_document_id' => 'required|exists:dts_documents,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                DtsDocRoute::where('id', $request->input('doc_route_id'))->delete();
                DtsDocument::where('id', $request->input('dts_document_id'))->delete();
                DB::table('dts_batch_submit_doc_route')
                    ->where('id', $request->input('id'))
                    ->delete();
            });

            return redirect()->back()->with('success', 'Document removed successfully.');
        } catch (\Exception $e) {
            Log::error('Batch submit remove document failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to remove document.');
        }
    }

    public function finalize(Request $request)
    {
        abort_if(Gate::denies('dts_batch_submit_finalize'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'batch_submit_id' => 'required|exists:dts_batch_submits,id',
        ]);

        $batch = DtsBatchSubmit::findOrFail($request->input('batch_submit_id'));

        if ($batch->submittedby_id !== null) {
            return redirect()->back()->with('error', 'Batch already submitted.');
        }

        $docCount = DB::table('dts_batch_submit_doc_route')
            ->where('batch_submit_id', $batch->id)
            ->count();

        if ($docCount === 0) {
            return redirect()->back()->with('error', 'Cannot submit an empty batch.');
        }

        $batch->update([
            'submittedby_id' => Auth::id(),
            'submit_date' => now(),
        ]);

        return redirect()->route('dts.batch-submits.index')->with('success', 'Batch ' . $batch->batch_code . ' submitted successfully with ' . $docCount . ' document(s).');
    }

    public function forPrintView($batchSubmitId)
    {
        abort_if(Gate::denies('dts_batch_submit_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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

        $batchSubmit = DtsBatchSubmit::with('createdBy', 'submittedBy')->findOrFail($batchSubmitId);

        $batchDocuments = DB::table('dts_batch_submit_doc_route')
            ->join('dts_doc_routes', 'dts_doc_routes.id', '=', 'dts_batch_submit_doc_route.doc_route_id')
            ->join('dts_documents', 'dts_documents.id', '=', 'dts_batch_submit_doc_route.dts_document_id')
            ->join('dts_doc_types', 'dts_doc_types.id', '=', 'dts_documents.dts_doc_type_id')
            ->select(
                'dts_documents.tracking_code',
                'dts_documents.description',
                'dts_doc_types.description as type_description'
            )
            ->where('dts_batch_submit_doc_route.batch_submit_id', $batchSubmitId)
            ->get();

        return view('dts.batch-submit-print', compact('mySection', 'myAllSections', 'batchSubmit', 'batchDocuments', 'systemSetting'));
    }
}
