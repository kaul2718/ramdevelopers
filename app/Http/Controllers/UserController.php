<?php
// filepath: app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = User::with(['roles', 'country']);
        
        // Si NO es Admin o DevAdmin, excluir usuarios con esos roles
        if (!auth()->user()->hasAnyRole(['Admin', 'DevAdmin'])) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['Admin', 'DevAdmin']);
            });
        }
        
        // Filtrar por país si el usuario actual es "Master Pais"
        if (auth()->user()->hasRole('Master Pais')) {
            $countryId = auth()->user()->usr_id_ctry;
            $query->where('usr_id_ctry', $countryId);
        }
        
        $users = $query->latest()
            ->paginate(10)
            ->through(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'phone' => $user->phone,
                'usr_id_ctry' => $user->usr_id_ctry,
                'usr_active' => $user->usr_active,
                'profile_photo_path' => $user->profile_photo_path,
                'country' => $user->country,
                'roles' => $user->roles,
                'idiomas' => $user->idiomas,
            ]);
        
        // Filtrar países: Master Pais solo ve su país
        $countriesQuery = Country::where('ctry_active', true)->orderBy('ctry_name');
        if (auth()->user()->hasRole('Master Pais')) {
            $countriesQuery->where('ctry_id', auth()->user()->usr_id_ctry);
        }
        $countries = $countriesQuery->get();
        
        // Si es Master Pais, mostrar solo roles de Agente Inmobiliario y Cliente
        // DevAdmin solo lo ve DevAdmin
        if (auth()->user()->hasRole('Master Pais')) {
            $roles = Role::whereIn('name', ['Agente Inmobiliario', 'Cliente'])->get();
        } else {
            $rolesQuery = Role::query();
            if (!auth()->user()->hasRole('DevAdmin')) {
                $rolesQuery->where('name', '!=', 'DevAdmin');
            }
            $roles = $rolesQuery->get();
        }
        
        $permissions = Permission::all();
        
        return Inertia::render('User/Index', [
            'users' => $users,
            'countries' => $countries,
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Filtrar países: Master Pais solo ve su país
        $countriesQuery = Country::where('ctry_active', true)->orderBy('ctry_name');
        if (auth()->user()->hasRole('Master Pais')) {
            $countriesQuery->where('ctry_id', auth()->user()->usr_id_ctry);
        }
        $countries = $countriesQuery->get();
        
        // Filtrar roles: DevAdmin solo lo ve DevAdmin
        $rolesQuery = Role::query();
        if (!auth()->user()->hasRole('DevAdmin')) {
            $rolesQuery->where('name', '!=', 'DevAdmin');
        }
        $roles = $rolesQuery->get();
        
        $permissions = Permission::all();
        return Inertia::render('User/Create', [
            'countries' => $countries,
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();
        
        // Convertir usr_active a boolean si viene como número
        if (isset($data['usr_active'])) {
            $data['usr_active'] = (bool) $data['usr_active'];
        }
        
        // Manejar la subida de foto de perfil
        if ($request->hasFile('profile_photo_path')) {
            $data['profile_photo_path'] = $request->file('profile_photo_path')->store('profile-photos', 'public');
        }
        
        $user = User::create($data);
        
        // Asignar roles: aceptar tanto IDs como nombres
        if ($request->has('roles')) {
            $roles = $request->roles;
            if (!empty($roles)) {
                if (collect($roles)->every(fn($r) => is_numeric($r))) {
                    $user->roles()->sync($roles);
                } else {
                    $user->syncRoles($roles);
                }
            }
        }
        
        // Asignar permisos directos (convertir IDs a nombres)
        if ($request->has('permissions')) {
            $permissionIds = $request->permissions;
            if (!empty($permissionIds)) {
                // Convertir IDs a nombres de permisos
                $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();
                $user->syncPermissions($permissionNames);
            }
        }
        
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['roles', 'country']);
        return Inertia::render('User/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'phone' => $user->phone,
                'idiomas' => $user->idiomas,
                'profile_photo_url' => $user->profile_photo_url,
                'usr_active' => $user->usr_active,
                'country' => $user->country,
                'roles' => $user->roles,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Filtrar países: Master Pais solo ve su país
        $countriesQuery = Country::where('ctry_active', true)->orderBy('ctry_name');
        if (auth()->user()->hasRole('Master Pais')) {
            $countriesQuery->where('ctry_id', auth()->user()->usr_id_ctry);
        }
        $countries = $countriesQuery->get();
        
        // Filtrar roles: DevAdmin solo lo ve DevAdmin
        $rolesQuery = Role::query();
        if (!auth()->user()->hasRole('DevAdmin')) {
            $rolesQuery->where('name', '!=', 'DevAdmin');
        }
        $roles = $rolesQuery->get();
        
        // Obtener permisos del usuario a través de sus roles
        $rolePermissionIds = [];
        foreach ($user->roles as $role) {
            $rolePermissionIds = array_merge($rolePermissionIds, $role->permissions->pluck('id')->toArray());
        }
        
        // Obtener permisos directos del usuario
        $userDirectPermissionIds = $user->getDirectPermissions()->pluck('id')->toArray();
        
        // Combinar y obtener IDs únicos de todos los permisos que el usuario ya tiene
        $userPermissionIds = array_unique(array_merge($rolePermissionIds, $userDirectPermissionIds));
        
        // Mostrar solo los permisos que NO están en los roles del usuario
        $permissions = Permission::whereNotIn('id', $rolePermissionIds)->get();
        
        return Inertia::render('User/Edit', [
            'user' => $user->load('roles', 'country'),
            'countries' => $countries,
            'roles' => $roles,
            'permissions' => $permissions,
            'userRoles' => $user->roles->pluck('id')->toArray(),
            'userDirectPermissions' => $user->getDirectPermissions()->pluck('id')->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        // IMPORTANTE: Capturar el archivo ANTES de validated() 
        // porque validated() no lo incluye si es null
        $photoFile = $request->file('profile_photo_path');
        
        $data = $request->validated();
        
        // Remover password_confirmation del array de datos
        unset($data['password_confirmation']);
        
        // Si los campos no vienen (Inertia no los envía si no cambiaron), usar los valores actuales
        if (is_null($data['name'] ?? null)) {
            $data['name'] = $user->name;
        }
        if (is_null($data['lastname'] ?? null)) {
            $data['lastname'] = $user->lastname;
        }
        if (is_null($data['email'] ?? null)) {
            $data['email'] = $user->email;
        }
        
        // Solo actualizar password si se proporciona una
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            // Hash la contraseña si se proporciona
            $data['password'] = bcrypt($data['password']);
        }
        
        // Convertir usr_active a boolean si viene como número
        if (isset($data['usr_active'])) {
            $data['usr_active'] = (bool) $data['usr_active'];
        }
        
        // Manejar la subida de foto de perfil
        if ($photoFile) {
            // Eliminar foto antigua si existe
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $data['profile_photo_path'] = $photoFile->store('profile-photos', 'public');
            \Log::info('Photo stored at: ' . $data['profile_photo_path']);
        } else {
            // Si no se sube una nueva foto, no modificar el campo
            unset($data['profile_photo_path']);
        }
        
        $user->update($data);
        
        // Sincronizar roles SOLO si se envían (no borrar si no vienen en la request)
        if ($request->has('roles')) {
            $roles = $request->roles;
            if (!empty($roles)) {
                if (collect($roles)->every(fn($r) => is_numeric($r))) {
                    $user->roles()->sync($roles);
                } else {
                    $user->syncRoles($roles);
                }
            } else {
                $user->roles()->sync([]);
            }
        }
        
        // Sincronizar permisos directos (convertir IDs a nombres)
        if ($request->has('permissions')) {
            $permissionIds = $request->permissions;
            if (!empty($permissionIds)) {
                // Convertir IDs a nombres de permisos
                $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();
                $user->syncPermissions($permissionNames);
            } else {
                $user->syncPermissions([]);
            }
        }
        
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }
}