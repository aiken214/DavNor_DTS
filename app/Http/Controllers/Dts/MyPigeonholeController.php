<?php

namespace App\Http\Controllers\Dts;

use App\Http\Controllers\Controller;
use App\Models\DtsDocRoute;
use App\Models\DtsSection;
use App\Models\DtsSystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MyPigeonholeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        abort_if(!$user->pigeonhole_id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $systemSetting = DtsSystemSetting::first();
        $mySection = null;
        $assignedSection = DtsSection::where('id', $user->section_id)->first();
        if ($assignedSection) {
            $mySection = $assignedSection->name;
        }
        $myAllSections = DB::table('section_user')
            ->join('dts_sections', 'dts_sections.id', '=', 'section_user.section_id')
            ->where('user_id', $user->id)
            ->orderBy('name', 'asc')
            ->get();

        $pigeonhole = $user->pigeonhole;

        $documents = DtsDocRoute::with(['document.docType', 'fromSection', 'fromUser', 'forSection'])
            ->where('pigeonhole_id', $user->pigeonhole_id)
            ->whereIn('status_id', [1, 4])
            ->orderBy('id', 'desc')
            ->paginate(500);

        return view('dts.my-pigeonhole', compact(
            'systemSetting', 'mySection', 'myAllSections', 'pigeonhole', 'documents'
        ));
    }

    public function reEntry(Request $request)
    {
        $user = Auth::user();
        abort_if(!$user->pigeonhole_id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validated = $request->validate([
            'doc_route_id' => 'required|numeric|exists:dts_doc_routes,id',
            'reentry_remarks' => 'nullable|string|max:255',
        ]);

        try {
            $pigeonholeRoute = DtsDocRoute::where('id', $validated['doc_route_id'])
                ->where('pigeonhole_id', $user->pigeonhole_id)
                ->where('status_id', 4)
                ->firstOrFail();

            DB::transaction(function () use ($pigeonholeRoute, $validated, $user) {
                $pigeonholeRoute->update([
                    'status_id' => 6,
                    'end_remarks' => $pigeonholeRoute->end_remarks . ' | Re-entered by ' . $user->name,
                ]);

                DtsDocRoute::create([
                    'dts_document_id' => $pigeonholeRoute->dts_document_id,
                    'previous_route_id' => $pigeonholeRoute->id,
                    'from_user_id' => $user->id,
                    'from_section_id' => $user->section_id,
                    'for_section_id' => $pigeonholeRoute->from_section_id,
                    'for_user_id' => $pigeonholeRoute->from_user_id,
                    'date_forwarded' => now(),
                    'status_id' => 1,
                    'route_purpose' => 'Re-entry from Pigeonhole' . ($validated['reentry_remarks'] ? ': ' . $validated['reentry_remarks'] : ''),
                ]);
            });

            return redirect()->route('dts.my-pigeonhole')->with('success', 'Document re-entered into the system successfully.');

        } catch (\Exception $e) {
            Log::error('Pigeonhole re-entry failed: ' . $e->getMessage());
            return redirect()->route('dts.my-pigeonhole')->with('error', 'Failed to re-enter document.');
        }
    }
}
