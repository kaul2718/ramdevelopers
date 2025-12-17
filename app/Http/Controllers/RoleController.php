<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\RoleRequest;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->latest()->paginate(10);
        return Inertia::render('Role/Index', ['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return Inertia::render('Role/Create', ['permissions' => $permissions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $data = $request->validated();

        // Determine guard name from existing permissions, fallback to default guard
        $guardName = Permission::count() ? Permission::first()->guard_name : config('auth.defaults.guard');

        $role = Role::create(['name' => $data['name'], 'guard_name' => $guardName]);

        if (!empty($data['permissions'])) {
            // only sync permissions that belong to the same guard as the role
            $perms = Permission::whereIn('id', $data['permissions'])->where('guard_name', $role->guard_name)->pluck('id')->toArray();
            $role->syncPermissions($perms);
        }

        return redirect()->route('roles.index');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return Inertia::render('Role/Edit', [
            'role' => $role->load('permissions'),
            'permissions' => $permissions,
            'rolePermissions' => $role->permissions->pluck('id')->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role)
    {
        $data = $request->validated();
        $role->name = $data['name'];
        $role->save();

        // Filter incoming permissions to those that match the role's guard
        $perms = Permission::whereIn('id', $data['permissions'] ?? [])->where('guard_name', $role->guard_name)->pluck('id')->toArray();
        $role->syncPermissions($perms);

        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $roleId = $role->id;
        // Eliminar relaciones y rol directamente desde BD para evitar eventos de Spatie con mismatch de guard
        DB::table('role_has_permissions')->where('role_id', $roleId)->delete();
        DB::table('model_has_roles')->where('role_id', $roleId)->delete();
        DB::table('roles')->where('id', $roleId)->delete();
        // Limpiar cache de Spatie
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return redirect()->route('roles.index');
    }
}

