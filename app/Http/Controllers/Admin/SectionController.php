<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DtsSection;
use App\Models\DtsSystemSetting;
use App\Models\DtsSectionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use App\Models\User;

class SectionController extends Controller
{
    /**
     * Display a listing of the sections.
     */
    public function index()
    {   
        $mySection = NULL;
        $systemSetting =DtsSystemSetting::first();
        $assignedSection=DtsSection::where('id', Auth::user()->section_id)->first();
        if ($assignedSection) {
            $mySection = $assignedSection->name;
        }
         $myAllSections= DB::table('section_user')->join('dts_sections', 'dts_sections.id','=','section_user.section_id')
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('name','asc')
                        ->get();
         $sectionCategories = DtsSectionCategory::all();               
         $sections = DtsSection::with('category')->where('id','>',1)->get(); // exluding the guest section id=1;
        return view('admin.sections.index', compact('sections', 'sectionCategories', 'mySection', 'myAllSections', 'systemSetting'));
    }

    public function create()
    {
        $sectionCategories = DtsSectionCategory::all();
        $users= User::select('id','name')->get();
        return view('admin.sections.create', compact('sectionCategories', 'users'));
    }

    /**
     * Store a newly created section in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:dts_sections',
            'is_record_management' => 'required|boolean',
            'is_public_dropdown' => 'required|boolean',
        ]);
        

       // dd($request->all());

        $createdsection = DtsSection::create($request->all());
        if($createdsection){
                return redirect()->route('admin.sections.index')->with('success', 'Section created successfully');
            }else{
                return redirect()->route('admin.sections.index')->with('error', 'Section not created');
            }
    }   

    /**
     * Show the form for editing the specified section.
     */
    public function edit($id)
    {
        $section = DtsSection::findOrFail($id);
        $sectionCategories = DtsSectionCategory::all();
        return view('admin.sections.edit', compact('section', 'sectionCategories'));
    }

    /**
     * Update the specified section in storage.
     */
    public function updateSection(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:dts_sections,name,' . $request->section_id,
            'is_record_management' => 'required|boolean',
            'is_public_dropdown' => 'required|boolean',
            
        ]);

        $section = DtsSection::findOrFail($request->section_id);
        $section->name = $request->name;
     //   $section->category_id = 1;
        $section->is_record_management = $request->is_record_management;
        $section->is_public_dropdown = $request->is_public_dropdown;
        $section->save();
       // $section->update($request->all());
        return redirect()->route('admin.sections.index')->with('success', 'Section updated successfully');
    }

    /**
     * Soft delete the specified section.
     */
    public function destroy(Request $request)
    {
        $id = $request->section_id;

        if($this->hasSectionUser($id)){
            return redirect()->route('admin.sections.index')->with('error', 'Section has users, cannot delete');
        }
    
        $section = DtsSection::findOrFail($id);
        $section->delete();

        return redirect()->route('admin.sections.index')->with('success', 'Section deleted successfully');
    }

    private function hasSectionUser($seectionId){
        $countUsers=  User::where('section_id', $seectionId)->count();
        if($countUsers>0){
            return true;
        }else{
            return false;
        }

    }
}

