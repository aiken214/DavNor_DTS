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

class KeptDocumentController extends Controller
{
    public function receivedKept()
    {

        abort_if(Gate::denies('dts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $systemSetting =DtsSystemSetting::first();
        $tableTitle="Documents Kept (no further actions needed)";      
       // Retrieve the aggregated count data for the user's section
       $mySection = NULL;
       $assignedSection=DtsSection::where('id', Auth::user()->section_id)->first();
       if ($assignedSection) {
           $mySection = $assignedSection->name;
       }
        $myAllSections= DB::table('section_user')->join('dts_sections', 'dts_sections.id','=','section_user.section_id')
                       ->where('user_id', Auth::user()->id)
                       ->orderBy('name','asc')
                       ->get();
        $currentUserStationId = Auth::user()->section_id;           
            $sections = DtsSection::select('id', 'name')
                           ->where('id', '!=', $currentUserStationId)
                           ->where('id','>',1) // Exclude the first section for GUEST
                           ->where('category_id', 1) // for division office
                           ->where('is_dropdown', true)
                           ->orderBy('name')
                           ->get();
        $documents = DtsDocRoute::with(['document', 'fromSection', 'fromUser'])
        ->where('for_section_id', Auth::user()->section_id)
        ->where('status_id', 3)//kept documents
        ->paginate(1000);

        return view('dts.kept-received-docs', compact('tableTitle','documents', 'sections','mySection', 'myAllSections','assignedSection', 'systemSetting'));
    }
}
