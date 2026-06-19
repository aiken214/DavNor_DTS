<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class AuthGates
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return $next($request);
        }

        $permissionsArray = Cache::remember('gate_permissions', 3600, function () {
            $roles = Role::with('permissions')->get();
            $permissions = [];
            foreach ($roles as $role) {
                foreach ($role->permissions as $permission) {
                    $permissions[$permission->title][] = $role->id;
                }
            }
            return $permissions;
        });

        foreach ($permissionsArray as $title => $roleIds) {
            Gate::define($title, function ($user) use ($roleIds) {
                return count(array_intersect($user->roles->pluck('id')->toArray(), $roleIds)) > 0;
            });
        }
        return $next($request);
    }
}
