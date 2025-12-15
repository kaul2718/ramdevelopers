<?php
// filepath: app/Http/Controllers/CountryController.php

namespace App\Http\Controllers;

use App\Http\Requests\CountryRequest;
use App\Models\Country;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = Country::latest()->paginate(10);
        return Inertia::render('Country/Index', ['countries' => $countries]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Country/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CountryRequest $request)
    {
        Country::create($request->validated());
        return redirect()->route('countries.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $ctry_id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        return Inertia::render('Country/Edit', [
            'country' => $country,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CountryRequest $request, Country $country)
    {
        $country->update($request->validated());
        return redirect()->route('countries.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        $country->delete();
        return redirect()->route('countries.index');
    }
}