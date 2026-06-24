<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DtsPigeonhole;
use App\Models\DtsSection;
use App\Models\User;
use App\Models\Role;
use App\Models\DtsSystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $systemSetting =DtsSystemSetting::first();
        $mySection = NULL;
        $assignedSection=DtsSection::where('id', Auth::user()->section_id)->first();
        if ($assignedSection) {
            $mySection = $assignedSection->name;
        }
         $myAllSections= DB::table('section_user')->join('dts_sections', 'dts_sections.id','=','section_user.section_id')
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('name','asc')
                        ->get();
 
        $users = User::with('section', 'roles', 'sections')->get();    
      
        return view('admin.users', compact('users','mySection', 'myAllSections', 'systemSetting'));


    }

    public function updateStation(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:dts_sections,id', // Ensure the station_id is valid
        ]);

        $user = Auth::user();
        $user->section_id = $request->station_id;
        $user->save();

        return redirect()->back()->with('success', 'My Station updated successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
        
        $sections = DtsSection::orderBy('name')->where('id','>',1)->get(); //excluding section ID 1 it belongs the GUest Section
        $roles= Role::orderBy('title')->get();
        $pigeonholes = DtsPigeonhole::where('is_active', true)->orderBy('name')->get();

       return view('admin.users.create' , compact('sections', 'mySection', 'myAllSections', 'roles', 'systemSetting', 'pigeonholes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

       $validated= $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'section_id' => 'nullable|exists:dts_sections,id',
            'roles' => 'nullable|array',
            'roles.*' => 'nullable|exists:roles,id',
            'sections' => 'nullable|array',
            'sections.*' => 'nullable|exists:dts_sections,id',
            'pigeonhole_id' => 'nullable|exists:dts_pigeonholes,id',
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->section_id = $request->input('section_id');
        $user->pigeonhole_id = $request->input('pigeonhole_id') ?: null;
        $user->save();

        // Assign roles to the user
        $user->roles()->sync($request->input('roles', []));

        // Assign sections to the user
        $user->sections()->sync($request->input('sections', []));

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
        
        $sections = DtsSection::orderBy('name')->where('id','>',1)->get(); //excluding section ID 1 it belongs the GUest Section
        $userSections = $user->sections->pluck('id')->toArray(); // Get the sections assigned to the user
        $userRoles = $user->roles->pluck('id')->toArray(); // Get the roles assigned to the user
        $roles= Role::orderBy('title')->get();
        $pigeonholes = DtsPigeonhole::where('is_active', true)->orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'sections', 'userSections', 'mySection', 'myAllSections', 'roles', 'userRoles', 'systemSetting', 'pigeonholes'));
      
    }

    /**
     * Update the specified user in storage.$sections = DtsSection::orderBy('name')->get();

     */
    public function update(Request $request, User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'section_id' => 'nullable|exists:dts_sections,id',
            'roles' => 'nullable|array',
            'roles.*' => 'nullable|exists:roles,id',
            'sections' => 'nullable|array',
            'sections.*' => 'nullable|exists:dts_sections,id',
            'pigeonhole_id' => 'nullable|exists:dts_pigeonholes,id',
        ]);

        if($request->section_id == ""){
            $section_id = NULL;
        } else {
            $section_id = $request->section_id;
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->section_id = $section_id;
        $user->pigeonhole_id = $request->input('pigeonhole_id') ?: null;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->save();
        // Sync roles
        $user->roles()->sync($request->input('roles', []));
        // Sync sections
        $user->sections()->sync($request->input('sections', []));

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user->delete(); // This will perform a soft delete if the User model uses the SoftDeletes trait

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }
}
