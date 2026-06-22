<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DtsSection;
use App\Models\DtsSystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class SectionStatisticsController extends Controller
{
    private function getCommonData()
    {
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
        $systemSetting = DtsSystemSetting::first();

        return compact('mySection', 'myAllSections', 'systemSetting');
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('dts_settings_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $commonData = $this->getCommonData();

        $sections = DtsSection::where('id', '>', 1)
            ->orderBy('name')
            ->get();

        $selectedSectionId = $request->query('section_id');
        $selectedSection = null;
        $sectionDocCounts = null;
        $sectionReceivedStats = null;
        $sectionForwardedStats = null;

        if ($selectedSectionId) {
            $selectedSection = DtsSection::find($selectedSectionId);

            if ($selectedSection) {
                $sectionDocCounts = DB::table('section_document_counts')
                    ->where('section_id', $selectedSectionId)
                    ->first();

                $sectionReceivedStats = DB::table('section_received_counts')
                    ->where('section_id', $selectedSectionId)
                    ->first();

                $sectionForwardedStats = DB::table('section_forwarded_counts')
                    ->where('section_id', $selectedSectionId)
                    ->first();
            }
        }

        return view('admin.section-statistics', array_merge($commonData, compact(
            'sections',
            'selectedSectionId',
            'selectedSection',
            'sectionDocCounts',
            'sectionReceivedStats',
            'sectionForwardedStats'
        )));
    }
}
