<?php

namespace App\Http\Controllers;

use App\Http\Requests\DevelopmentRequest;
use App\Models\Development;
use App\Models\Developer;
use App\Models\Country;
use App\Models\City;
use App\Models\ApprovalStatus;
use App\Models\BusinessState;
use App\Models\CommercialStatus;
use App\Models\Currency;
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
        $query = Development::with(['user', 'developer', 'country', 'city', 'approvalStatus', 'businessStatus', 'commercialStatus', 'currency', 'images']);
        
        // Filtrar por país si el usuario es "Master Pais"
        if (auth()->user()->hasRole('Master Pais')) {
            $query->where('ctry_id', auth()->user()->usr_id_ctry);
        }
        
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
        
        // Filtrar países: Master Pais solo ve su país
        $countriesQuery = Country::where('ctry_active', true)->with(['cities' => function($query) {
            $query->where('city_active', true)->orderBy('city_name');
        }])->orderBy('ctry_name');
        if (auth()->user()->hasRole('Master Pais')) {
            $countriesQuery->where('ctry_id', auth()->user()->usr_id_ctry);
        }
        $countries = $countriesQuery->get();
        
        $cities = City::where('city_active', true)->orderBy('city_name')->get();
        $approvalStatuses = ApprovalStatus::all();
        $businessStates = BusinessState::all();
        $commercialStatuses = CommercialStatus::all();
        $housingTypes = HousingType::where('houTyp_active', true)->orderBy('houTyp_name')->get();
        $documentTypes = DocumentType::all();
        $currencies = Currency::where('curr_active', true)->orderBy('curr_name')->get();
        
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
            'currencies' => $currencies,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $developers = Developer::where('devr_active', true)->get();
        
        // Filtrar países: Master Pais solo ve su país
        $countriesQuery = Country::where('ctry_active', true)->with(['cities' => function($query) {
            $query->where('city_active', true)->orderBy('city_name');
        }])->orderBy('ctry_name');
        if (auth()->user()->hasRole('Master Pais')) {
            $countriesQuery->where('ctry_id', auth()->user()->usr_id_ctry);
        }
        $countries = $countriesQuery->get();
        
        $cities = City::where('city_active', true)->orderBy('city_name')->get();
        $approvalStatuses = ApprovalStatus::all();
        $businessStates = BusinessState::all();
        $commercialStatuses = CommercialStatus::all();
        $housingTypes = HousingType::orderBy('houTyp_name')->get();
        $currencies = Currency::where('curr_active', true)->orderBy('curr_name')->get();
               
        return Inertia::render('Development/Create', [
            'developers' => $developers,
            'countries' => $countries,
            'cities' => $cities,
            'approvalStatuses' => $approvalStatuses,
            'businessStates' => $businessStates,
            'commercialStatuses' => $commercialStatuses,
            'housingTypes' => $housingTypes,
            'currencies' => $currencies,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DevelopmentRequest $request)
    {
        $validated = $request->validated();
        
        // Si es Agente Inmobiliario, asignar el proyecto a sí mismo
        if (auth()->user()->hasRole('Agente Inmobiliario')) {
            $validated['user_id'] = auth()->user()->id;
        }
        
        $development = Development::create($validated);
        
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
        $development->load(['developer', 'country', 'city', 'approvalStatus', 'businessStatus', 'commercialStatus', 'housingType', 'currency', 'files', 'images']);
        
        // Cargar captadores con detalles del usuario
        $captors = $development->developmentCaptors()
            ->with('user')
            ->get()
            ->map(function ($captor) {
                return [
                    'devt_id' => $captor->devt_id,
                    'user_id' => $captor->user_id,
                    'devtUsr_is_main' => $captor->devtUsr_is_main,
                    'created_at' => $captor->created_at,
                    'updated_at' => $captor->updated_at,
                    'user' => $captor->user ? [
                        'id' => $captor->user->id,
                        'name' => $captor->user->name,
                        'lastname' => $captor->user->lastname,
                        'email' => $captor->user->email,
                        'phone' => $captor->user->phone,
                    ] : null,
                ];
            });
        
        return Inertia::render('Development/Show', [
            'development' => $development,
            'captors' => $captors,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Development $development)
    {
        // Verificar que el Agente Inmobiliario solo pueda editar sus propios proyectos
        if (auth()->user()->hasRole('Agente Inmobiliario') && $development->user_id !== auth()->user()->id) {
            abort(403, 'No autorizado');
        }

        $developers = Developer::where('devr_active', true)->get();
        
        // Filtrar países: Master Pais solo ve su país
        $countriesQuery = Country::where('ctry_active', true)->with(['cities' => function($query) {
            $query->where('city_active', true)->orderBy('city_name');
        }])->orderBy('ctry_name');
        if (auth()->user()->hasRole('Master Pais')) {
            $countriesQuery->where('ctry_id', auth()->user()->usr_id_ctry);
        }
        $countries = $countriesQuery->get();
        
        $cities = City::where('city_active', true)->orderBy('city_name')->get();
        $approvalStatuses = ApprovalStatus::all();
        $businessStates = BusinessState::all();
        $commercialStatuses = CommercialStatus::all();
        $housingTypes = HousingType::where('houTyp_active', true)->orderBy('houTyp_name')->get();
        $currencies = Currency::where('curr_active', true)->orderBy('curr_name')->get();
        $documentTypes = DocumentType::all();
        
        $development->load(['developer', 'country', 'city', 'approvalStatus', 'businessStatus', 'commercialStatus', 'housingType', 'currency', 'files', 'images']);
        
        return Inertia::render('Development/Edit', [
            'development' => $development,
            'developers' => $developers,
            'countries' => $countries,
            'cities' => $cities,
            'approvalStatuses' => $approvalStatuses,
            'businessStates' => $businessStates,
            'commercialStatuses' => $commercialStatuses,
            'housingTypes' => $housingTypes,
            'currencies' => $currencies,
            'documentTypes' => $documentTypes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DevelopmentRequest $request, Development $development)
    {
        // Verificar que el Agente Inmobiliario solo pueda editar sus propios proyectos
        if (auth()->user()->hasRole('Agente Inmobiliario') && $development->user_id !== auth()->user()->id) {
            abort(403, 'No autorizado');
        }

        $validated = $request->validated();
        
        // Si es Agente Inmobiliario, mantener su ID
        if (auth()->user()->hasRole('Agente Inmobiliario')) {
            $validated['user_id'] = $development->user_id;
        }
        
        $development->update($validated);
        return redirect()->route('development.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Development $development)
    {
        // Verificar que el Agente Inmobiliario solo pueda eliminar sus propios proyectos
        if (auth()->user()->hasRole('Agente Inmobiliario') && $development->user_id !== auth()->user()->id) {
            abort(403, 'No autorizado');
        }

        $development->delete();
        return redirect()->route('development.index');
    }
}

