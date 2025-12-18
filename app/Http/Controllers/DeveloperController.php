<?php
// filepath: app/Http/Controllers/DeveloperController.php

namespace App\Http\Controllers;

use App\Http\Requests\DeveloperRequest;
use App\Models\Developer;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class DeveloperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $developers = Developer::with(['country', 'user'])->latest()->paginate(10);
        return Inertia::render('Developer/Index', ['developers' => $developers]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::where('ctry_active', true)->get();
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'promotor');
        })->where('usr_active', true)->get();
        return Inertia::render('Developer/Create', [
            'countries' => $countries,
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DeveloperRequest $request)
    {
        $data = $request->validated();
        // Por defecto, el desarrollador se crea activo
        $data['devr_active'] = true;
        Developer::create($data);
        return redirect()->route('developers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Developer $developer)
    {
        $countries = Country::where('ctry_active', true)->get();
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'promotor');
        })->where('usr_active', true)->get();
        return Inertia::render('Developer/Edit', [
            'developer' => $developer,
            'countries' => $countries,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DeveloperRequest $request, Developer $developer)
    {
        $developer->update($request->validated());
        return redirect()->route('developers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Developer $developer)
    {
        $developer->delete();
        return redirect()->route('developers.index');
    }
}
