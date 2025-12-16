<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\PermissionRequest;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::latest()->paginate(10);
        return Inertia::render('Permission/Index', ['permissions' => $permissions]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Permission/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request)
    {
        $data = $request->validated();

        // Use default guard or 'web' if not specified
        $guardName = $request->input('guard_name', 'web');

        Permission::create([
            'name' => $data['name'],
            'guard_name' => $guardName,
        ]);

        return redirect()->route('permissions.index');
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
    public function edit(Permission $permission)
    {
        return Inertia::render('Permission/Edit', [
            'permission' => $permission,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, Permission $permission)
    {
        $data = $request->validated();

        $permission->update([
            'name' => $data['name'],
        ]);

        return redirect()->route('permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        // Delete role_has_permissions relationships directly
        DB::table('role_has_permissions')
            ->where('permission_id', $permission->id)
            ->delete();

        // Delete model_has_permissions relationships directly
        DB::table('model_has_permissions')
            ->where('permission_id', $permission->id)
            ->delete();

        // Delete the permission
        $permission->delete();

        // Clear cache
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('permissions.index');
    }
}
