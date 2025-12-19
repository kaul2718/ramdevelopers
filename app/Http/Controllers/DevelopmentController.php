<?php
// filepath: app/Http/Controllers/DevelopmentController.php

namespace App\Http\Controllers;

use App\Http\Requests\DevelopmentRequest;
use App\Models\Development;
use App\Models\Developer;
use App\Models\Country;
use App\Models\City;
use App\Models\ApprovalStatus;
use App\Models\BusinessState;
use App\Models\CommercialStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DevelopmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $developments = Development::with(['developer', 'country', 'city', 'approvalStatus', 'businessStatus', 'commercialStatus'])
            ->latest()
            ->paginate(10);
        return Inertia::render('Development/Index', ['developments' => $developments]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $developers = Developer::where('devr_active', true)->get();
        $countries = Country::where('ctry_active', true)->get();
        $cities = City::where('city_active', true)->get();
        $approvalStatuses = ApprovalStatus::all();
        $businessStates = BusinessState::all();
        $commercialStatuses = CommercialStatus::all();
        
        return Inertia::render('Development/Create', [
            'developers' => $developers,
            'countries' => $countries,
            'cities' => $cities,
            'approvalStatuses' => $approvalStatuses,
            'businessStates' => $businessStates,
            'commercialStatuses' => $commercialStatuses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DevelopmentRequest $request)
    {
        $development = Development::create($request->validated());
        return redirect()->route('developmentfile.create', ['development_id' => $development->devt_id])
                        ->with('message', 'Desarrollo creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Development $development)
    {
        $development->load(['developer', 'country', 'city', 'approvalStatus', 'businessStatus', 'commercialStatus', 'files', 'images']);
        
        return Inertia::render('Development/Show', [
            'development' => $development,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Development $development)
    {
        $developers = Developer::where('devr_active', true)->get();
        $countries = Country::where('ctry_active', true)->get();
        $cities = City::where('city_active', true)->get();
        $approvalStatuses = ApprovalStatus::all();
        $businessStates = BusinessState::all();
        $commercialStatuses = CommercialStatus::all();
        
        $development->load(['developmentFiles', 'developmentImages']);
        
        return Inertia::render('Development/Edit', [
            'development' => $development,
            'developers' => $developers,
            'countries' => $countries,
            'cities' => $cities,
            'approvalStatuses' => $approvalStatuses,
            'businessStates' => $businessStates,
            'commercialStatuses' => $commercialStatuses,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DevelopmentRequest $request, Development $development)
    {
        $development->update($request->validated());
        return redirect()->route('development.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Development $development)
    {
        $development->delete();
        return redirect()->route('development.index');
    }
}

