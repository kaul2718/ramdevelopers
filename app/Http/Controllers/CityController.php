<?php
// filepath: app/Http/Controllers/CityController.php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = City::with('country')->latest()->paginate(10);
        return Inertia::render('City/Index', ['cities' => $cities]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::where('ctry_active', true)->get();
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
        $countries = Country::where('ctry_active', true)->get();
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