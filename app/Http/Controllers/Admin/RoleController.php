<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\DtsSection;
use App\Models\User;
use App\Models\DtsSystemSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
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

    private function groupPermissions($permissions)
    {
        $grouped = [];
        foreach ($permissions as $permission) {
            $title = $permission->title;

            if (str_starts_with($title, 'dts_batch_release')) {
                $group = 'DTS Batch Release';
            } elseif (str_starts_with($title, 'dts_doctype')) {
                $group = 'DTS Doc Types';
            } elseif (str_starts_with($title, 'dts_document') || str_starts_with($title, 'dts_doc_')) {
                $group = 'DTS Documents';
            } elseif (str_starts_with($title, 'dts_guest_doc')) {
                $group = 'DTS Guest Documents';
            } elseif (str_starts_with($title, 'dts_records')) {
                $group = 'DTS Records Management';
            } elseif (str_starts_with($title, 'dts_reports')) {
                $group = 'DTS Reports';
            } elseif (str_starts_with($title, 'dts_route')) {
                $group = 'DTS Routing';
            } elseif (str_starts_with($title, 'dts_section')) {
                $group = 'DTS Sections';
            } elseif (str_starts_with($title, 'dts_settings')) {
                $group = 'DTS Settings';
            } elseif (str_starts_with($title, 'dts_system')) {
                $group = 'DTS System';
            } elseif (str_starts_with($title, 'dts_user')) {
                $group = 'DTS Users';
            } elseif (str_starts_with($title, 'dts_print')) {
                $group = 'DTS Other';
            } elseif (str_starts_with($title, 'dts_')) {
                $group = 'DTS General';
            } elseif (str_starts_with($title, 'user_')) {
                $group = 'User Management';
            } elseif (str_starts_with($title, 'role_')) {
                $group = 'Role Management';
            } elseif (str_starts_with($title, 'permission_')) {
                $group = 'Permission Management';
            } else {
                $group = 'Other';
            }

            $grouped[$group][] = $permission;
        }
        ksort($grouped);
        return $grouped;
    }

    public function index()
    {
        abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $commonData = $this->getCommonData();
        $roles = Role::with('permissions')->get();

        return view('admin.roles', array_merge($commonData, compact('roles')));
    }

    public function create()
    {
        abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $commonData = $this->getCommonData();
        $permissions = Permission::orderBy('title')->get();
        $groupedPermissions = $this->groupPermissions($permissions);

        return view('admin.roles.create', array_merge($commonData, compact('groupedPermissions')));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'title' => 'required|string|max:255|unique:roles,title',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['title' => $request->title]);
        $role->permissions()->sync($request->input('permissions', []));

        Cache::forget('gate_permissions');

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $commonData = $this->getCommonData();
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::orderBy('title')->get();
        $groupedPermissions = $this->groupPermissions($permissions);
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', array_merge($commonData, compact('role', 'groupedPermissions', 'rolePermissionIds')));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'title' => 'required|string|max:255|unique:roles,title,' . $id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::findOrFail($id);
        $role->update(['title' => $request->title]);
        $role->permissions()->sync($request->input('permissions', []));

        Cache::forget('gate_permissions');

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role = Role::findOrFail($id);

        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')->with('error', 'Cannot delete role. It is assigned to ' . $role->users()->count() . ' user(s).');
        }

        $role->permissions()->detach();
        $role->delete();

        Cache::forget('gate_permissions');

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
