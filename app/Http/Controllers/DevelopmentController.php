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
use App\Models\HousingType;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\DocumentType;

class DevelopmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Development::with(['developer', 'country', 'city', 'approvalStatus', 'businessStatus', 'commercialStatus', 'images']);
        
        // Filtro de búsqueda múltiple por palabras
        $search = $request->get('search', '');
        if ($search) {
            $words = collect(array_filter(explode(' ', trim($search))));
            
            if ($words->isNotEmpty()) {
                $query->where(function ($query) use ($words) {
                    foreach ($words as $word) {
                        $query->where(function ($q) use ($word) {
                            $q->whereRaw('LOWER(devt_title) LIKE ?', ["%{$word}%"])
                              ->orWhereRaw('LOWER(devt_short_description) LIKE ?', ["%{$word}%"])
                              ->orWhereRaw('LOWER(devt_long_description) LIKE ?', ["%{$word}%"])
                              ->orWhereHas('developer', function ($q) use ($word) {
                                  $q->whereRaw('LOWER(devr_commercial_name) LIKE ?', ["%{$word}%"]);
                              })
                              ->orWhereHas('country', function ($q) use ($word) {
                                  $q->whereRaw('LOWER(ctry_name) LIKE ?', ["%{$word}%"]);
                              })
                              ->orWhereHas('city', function ($q) use ($word) {
                                  $q->whereRaw('LOWER(city_name) LIKE ?', ["%{$word}%"]);
                              });
                        });
                    }
                });
            }
        }
        
        $developments = $query->latest()->paginate(10)->appends(['search' => $search]);
        
        $developers = Developer::where('devr_active', true)->get();
        $countries = Country::where('ctry_active', true)->with(['cities' => function($query) {
            $query->where('city_active', true)->orderBy('city_name');
        }])->orderBy('ctry_name')->get();
        $cities = City::where('city_active', true)->orderBy('city_name')->get();
        $approvalStatuses = ApprovalStatus::all();
        $businessStates = BusinessState::all();
        $commercialStatuses = CommercialStatus::all();
        $housingTypes = HousingType::where('houTyp_active', true)->orderBy('houTyp_name')->get();
        $documentTypes = DocumentType::all();
        
        return Inertia::render('Development/Index', [
            'developments' => $developments,
            'developers' => $developers,
            'countries' => $countries,
            'cities' => $cities,
            'approvalStatuses' => $approvalStatuses,
            'businessStates' => $businessStates,
            'commercialStatuses' => $commercialStatuses,
            'housingTypes' => $housingTypes,
            'documentTypes' => $documentTypes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $developers = Developer::where('devr_active', true)->get();
        $countries = Country::where('ctry_active', true)->with(['cities' => function($query) {
            $query->where('city_active', true)->orderBy('city_name');
        }])->orderBy('ctry_name')->get();
        $cities = City::where('city_active', true)->orderBy('city_name')->get();
        $approvalStatuses = ApprovalStatus::all();
        $businessStates = BusinessState::all();
        $commercialStatuses = CommercialStatus::all();
        $housingTypes = HousingType::orderBy('houTyp_name')->get();
        
        \Log::info('=== DEVELOPMENT CREATE METHOD ===');
        \Log::info('HousingTypes count: ' . $housingTypes->count());
        \Log::info('HousingTypes: ' . json_encode($housingTypes));
        
        return Inertia::render('Development/Create', [
            'developers' => $developers,
            'countries' => $countries,
            'cities' => $cities,
            'approvalStatuses' => $approvalStatuses,
            'businessStates' => $businessStates,
            'commercialStatuses' => $commercialStatuses,
            'housingTypes' => $housingTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DevelopmentRequest $request)
    {
        $development = Development::create($request->validated());
        
        // Devolver JSON para peticiones AJAX/Axios
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Desarrollo creado correctamente.',
                'development' => $development
            ], 201);
        }
        
        // Redirigir para peticiones tradicionales
        return redirect()->route('developmentfile.create', ['development_id' => $development->devt_id])
                        ->with('message', 'Desarrollo creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Development $development)
    {
        $development->load(['developer', 'country', 'city', 'approvalStatus', 'businessStatus', 'commercialStatus', 'housingType', 'files', 'images']);
        
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
        $countries = Country::where('ctry_active', true)->with(['cities' => function($query) {
            $query->where('city_active', true)->orderBy('city_name');
        }])->orderBy('ctry_name')->get();
        $cities = City::where('city_active', true)->orderBy('city_name')->get();
        $approvalStatuses = ApprovalStatus::all();
        $businessStates = BusinessState::all();
        $commercialStatuses = CommercialStatus::all();
        $housingTypes = HousingType::where('houTyp_active', true)->orderBy('houTyp_name')->get();
        $documentTypes = DocumentType::all();
        
        $development->load(['developer', 'country', 'city', 'approvalStatus', 'businessStatus', 'commercialStatus', 'housingType', 'files', 'images']);
        
        return Inertia::render('Development/Edit', [
            'development' => $development,
            'developers' => $developers,
            'countries' => $countries,
            'cities' => $cities,
            'approvalStatuses' => $approvalStatuses,
            'businessStates' => $businessStates,
            'commercialStatuses' => $commercialStatuses,
            'housingTypes' => $housingTypes,
            'documentTypes' => $documentTypes,
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

