<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DtsPigeonhole;
use App\Models\DtsSection;
use App\Models\DtsSystemSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PigeonholeController extends Controller
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

    public function index()
    {
        abort_if(Gate::denies('dts_settings_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $commonData = $this->getCommonData();
        $pigeonholes = DtsPigeonhole::with('section')->orderBy('name')->get();
        $sections = DtsSection::where('id', '>', 1)
            ->where('category_id', 1)
            ->orderBy('name')
            ->get();

        return view('admin.pigeonholes.index', array_merge($commonData, compact('pigeonholes', 'sections')));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('dts_settings_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'name' => 'required|string|max:255',
            'section_id' => 'required|exists:dts_sections,id',
            'description' => 'nullable|string|max:255',
        ]);

        DtsPigeonhole::create($request->only(['name', 'section_id', 'description']));

        return redirect()->route('admin.pigeonholes.index')->with('success', 'Pigeonhole created successfully.');
    }

    public function update(Request $request)
    {
        abort_if(Gate::denies('dts_settings_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'pigeonhole_id' => 'required|exists:dts_pigeonholes,id',
            'name' => 'required|string|max:255',
            'section_id' => 'required|exists:dts_sections,id',
            'description' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $pigeonhole = DtsPigeonhole::findOrFail($request->pigeonhole_id);
        $pigeonhole->update($request->only(['name', 'section_id', 'description', 'is_active']));

        return redirect()->route('admin.pigeonholes.index')->with('success', 'Pigeonhole updated successfully.');
    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('dts_settings_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'pigeonhole_id' => 'required|exists:dts_pigeonholes,id',
        ]);

        $pigeonhole = DtsPigeonhole::findOrFail($request->pigeonhole_id);

        $activeRoutes = $pigeonhole->docRoutes()->whereNull('date_accepted')->count();
        if ($activeRoutes > 0) {
            return redirect()->route('admin.pigeonholes.index')
                ->with('error', 'Cannot delete pigeonhole. It has ' . $activeRoutes . ' document(s) pending pickup.');
        }

        $pigeonhole->delete();

        return redirect()->route('admin.pigeonholes.index')->with('success', 'Pigeonhole deleted successfully.');
    }
}
