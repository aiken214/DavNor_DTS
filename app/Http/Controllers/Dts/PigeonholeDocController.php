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
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PigeonholeDocController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $systemSetting = DtsSystemSetting::first();
        $mySection = null;
        $assignedSection = DtsSection::where('id', Auth::user()->section_id)->first();
        $isAdmin = Gate::allows('dts_settings_access');
        abort_if(!$isAdmin && (!$assignedSection || !$assignedSection->is_record_management), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($assignedSection) {
            $mySection = $assignedSection->name;
        }
        $myAllSections = DB::table('section_user')
            ->join('dts_sections', 'dts_sections.id', '=', 'section_user.section_id')
            ->where('user_id', Auth::user()->id)
            ->orderBy('name', 'asc')
            ->get();

        $pigeonholes = DtsPigeonhole::with('section')
            ->where('is_active', true)
            ->withCount(['docRoutes as pending_count' => function ($query) {
                $query->whereIn('status_id', [1, 4]);
            }])
            ->orderBy('name')
            ->get();

        return view('dts.pigeonhole-docs', compact('systemSetting', 'mySection', 'myAllSections', 'pigeonholes'));
    }

    public function show($id)
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $systemSetting = DtsSystemSetting::first();
        $mySection = null;
        $assignedSection = DtsSection::where('id', Auth::user()->section_id)->first();
        $isAdmin = Gate::allows('dts_settings_access');
        abort_if(!$isAdmin && (!$assignedSection || !$assignedSection->is_record_management), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($assignedSection) {
            $mySection = $assignedSection->name;
        }
        $myAllSections = DB::table('section_user')
            ->join('dts_sections', 'dts_sections.id', '=', 'section_user.section_id')
            ->where('user_id', Auth::user()->id)
            ->orderBy('name', 'asc')
            ->get();

        $pigeonhole = DtsPigeonhole::with('section')->findOrFail($id);

        $documents = DtsDocRoute::with(['document', 'fromSection', 'fromUser'])
            ->where('pigeonhole_id', $id)
            ->whereIn('status_id', [1, 4])
            ->orderBy('id', 'desc')
            ->paginate(500);

        return view('dts.pigeonhole-docs-show', compact('systemSetting', 'mySection', 'myAllSections', 'pigeonhole', 'documents'));
    }

    public function release(Request $request)
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validated = $request->validate([
            'doc_route_id' => 'required|numeric|exists:dts_doc_routes,id',
            'pigeonhole_id' => 'required|numeric|exists:dts_pigeonholes,id',
            'release_remarks' => 'nullable|string|max:255',
        ]);

        try {
            $docRoute = DtsDocRoute::where('id', $validated['doc_route_id'])
                ->where('pigeonhole_id', $validated['pigeonhole_id'])
                ->where('status_id', 1)
                ->firstOrFail();

            $pigeonhole = DtsPigeonhole::findOrFail($validated['pigeonhole_id']);

            $docRoute->status_id = 4;
            $docRoute->date_accepted = now();
            $docRoute->receiver_user_id = Auth::id();
            $docRoute->date_acted = now();
            $docRoute->actedby_user_id = Auth::id();
            $docRoute->out_released_to = $pigeonhole->name;
            $docRoute->end_remarks = 'Released to Pigeonhole: ' . $pigeonhole->name . ' by ' . Auth::user()->name . ($validated['release_remarks'] ? ' | ' . $validated['release_remarks'] : '');
            $docRoute->save();

            return redirect()->route('dts.pigeonhole-docs.show', $validated['pigeonhole_id'])
                ->with('success', 'Document released successfully.');

        } catch (\Exception $e) {
            Log::error('Pigeonhole release failed: ' . $e->getMessage());
            return redirect()->route('dts.pigeonhole-docs.show', $validated['pigeonhole_id'])
                ->with('error', 'Failed to release document.');
        }
    }

    public function cancel(Request $request)
    {
        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validated = $request->validate([
            'doc_route_id' => 'required|numeric|exists:dts_doc_routes,id',
            'pigeonhole_id' => 'required|numeric|exists:dts_pigeonholes,id',
            'cancel_reason' => 'required|string|max:255',
        ]);

        try {
            $docRoute = DtsDocRoute::where('id', $validated['doc_route_id'])
                ->where('pigeonhole_id', $validated['pigeonhole_id'])
                ->where('status_id', 1)
                ->firstOrFail();

            DB::transaction(function () use ($docRoute, $validated) {
                if ($docRoute->previous_route_id) {
                    $prevRoute = DtsDocRoute::find($docRoute->previous_route_id);
                    if ($prevRoute) {
                        $prevRoute->update([
                            'status_id' => 2,
                            'date_acted' => null,
                            'actedby_user_id' => null,
                            'end_remarks' => null,
                        ]);
                    }
                }

                $docRoute->update([
                    'status_id' => 11,
                    'del_reason' => $validated['cancel_reason'] . ' : Cancelled by ' . Auth::user()->name,
                ]);
                $docRoute->delete();
            });

            return redirect()->route('dts.pigeonhole-docs.show', $validated['pigeonhole_id'])
                ->with('success', 'Pigeonhole document cancelled. Document returned to Received.');

        } catch (\Exception $e) {
            Log::error('Pigeonhole cancel failed: ' . $e->getMessage());
            return redirect()->route('dts.pigeonhole-docs.show', $validated['pigeonhole_id'])
                ->with('error', 'Failed to cancel document.');
        }
    }
}
