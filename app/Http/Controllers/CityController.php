<?php
// filepath: app/Http/Controllers/CityController.php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $query = City::with('country');
        
        // Si el usuario tiene rol 'Master Pais', mostrar solo ciudades de sus países
        if ($user->hasRole('Master Pais')) {
            $userCountryId = $user->usr_id_ctry;
            if ($userCountryId) {
                $query->where('ctry_id', $userCountryId);
            }
        }
        
        $cities = $query->orderBy('city_name')->paginate(10);
        
        $countriesQuery = Country::where('ctry_active', true);
        // Si es Master Pais, solo mostrar su país en el filtro
        if ($user->hasRole('Master Pais') && $user->usr_id_ctry) {
            $countriesQuery->where('ctry_id', $user->usr_id_ctry);
        }
        $countries = $countriesQuery->orderBy('ctry_name')->get();
        
        return Inertia::render('City/Index', [
            'cities' => $cities,
            'countries' => $countries
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $countriesQuery = Country::where('ctry_active', true);
        
        // Si es Master Pais, solo permitir crear ciudades en su país
        if ($user->hasRole('Master Pais') && $user->usr_id_ctry) {
            $countriesQuery->where('ctry_id', $user->usr_id_ctry);
        }
        
        $countries = $countriesQuery->orderBy('ctry_name')->get();
        return Inertia::render('City/Create', ['countries' => $countries]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CityRequest $request)
    {
        City::create($request->validated());
        return redirect()->route('cities.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $city_id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city)
    {
        $user = Auth::user();
        
        // Si es Master Pais, verificar que la ciudad pertenezca a su país
        if ($user->hasRole('Master Pais') && $city->ctry_id !== $user->usr_id_ctry) {
            abort(403, 'No tienes permiso para editar esta ciudad.');
        }
        
        $countriesQuery = Country::where('ctry_active', true);
        // Si es Master Pais, solo permitir asignar su país
        if ($user->hasRole('Master Pais') && $user->usr_id_ctry) {
            $countriesQuery->where('ctry_id', $user->usr_id_ctry);
        }
        $countries = $countriesQuery->get();
        
        return Inertia::render('City/Edit', [
            'city' => $city,
            'countries' => $countries,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CityRequest $request, City $city)
    {
        $user = Auth::user();
        
        // Si es Master Pais, verificar que la ciudad pertenezca a su país
        if ($user->hasRole('Master Pais') && $city->ctry_id !== $user->usr_id_ctry) {
            abort(403, 'No tienes permiso para actualizar esta ciudad.');
        }
        
        $city->update($request->validated());
        return redirect()->route('cities.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        $city->delete();
        return redirect()->route('cities.index');
    }
}