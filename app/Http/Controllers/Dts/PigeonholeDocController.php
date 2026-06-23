<?php

namespace App\Http\Controllers\Dts;

use App\Http\Controllers\Controller;
use App\Models\DtsDocRoute;
use App\Models\DtsPigeonhole;
use App\Models\DtsSection;
use App\Models\DtsSystemSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
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
                $query->where('status_id', 1);
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
            ->orderBy('id', 'desc')
            ->paginate(500);

        return view('dts.pigeonhole-docs-show', compact('systemSetting', 'mySection', 'myAllSections', 'pigeonhole', 'documents'));
    }
}
