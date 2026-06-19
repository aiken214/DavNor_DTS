<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\DtsSection;
use App\Models\DtsSystemSetting;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends Controller
{
    private function getCommonData()
    {
        $mySection = null;
        $systemSetting = DtsSystemSetting::first();
        $assignedSection = DtsSection::where('id', Auth::user()->section_id)->first();
        if ($assignedSection) {
            $mySection = $assignedSection->name;
        }
        $myAllSections = DB::table('section_user')
            ->join('dts_sections', 'dts_sections.id', '=', 'section_user.section_id')
            ->where('user_id', Auth::user()->id)
            ->orderBy('name', 'asc')
            ->get();

        return compact('mySection', 'myAllSections', 'systemSetting');
    }

    public function index()
    {
        abort_if(Gate::denies('permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $commonData = $this->getCommonData();
        $permissions = Permission::with('roles')->orderBy('title')->get();

        return view('admin.permissions.index', array_merge($commonData, compact('permissions')));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'title' => 'required|string|max:255|unique:permissions,title',
            'remarks' => 'nullable|string|max:255',
        ]);

        Permission::create([
            'title' => $request->title,
            'group' => $request->group,
            'remarks' => $request->remarks,
        ]);

        Cache::forget('gate_permissions');

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    public function update(Request $request)
    {
        abort_if(Gate::denies('permission_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'permission_id' => 'required|exists:permissions,id',
            'title' => 'required|string|max:255|unique:permissions,title,' . $request->permission_id,
            'remarks' => 'nullable|string|max:255',
        ]);

        $permission = Permission::findOrFail($request->permission_id);
        $permission->update([
            'title' => $request->title,
            'remarks' => $request->remarks,
        ]);

        Cache::forget('gate_permissions');

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('permission_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permission = Permission::findOrFail($request->permission_id);

        if ($permission->roles()->count() > 0) {
            return redirect()->route('admin.permissions.index')->with('error', 'Cannot delete permission. It is assigned to ' . $permission->roles()->count() . ' role(s). Remove it from all roles first.');
        }

        $permission->delete();

        Cache::forget('gate_permissions');

        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
