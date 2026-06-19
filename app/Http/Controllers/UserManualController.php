<?php

namespace App\Http\Controllers;

use App\Models\DtsSection;
use App\Models\DtsSystemSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserManualController extends Controller
{
    public function index()
    {
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

        return view('manual.index', compact('systemSetting', 'mySection', 'myAllSections'));
    }
}
