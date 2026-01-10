<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Development;
use App\Models\Country;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Lead::with(['development', 'country', 'source', 'status', 'user', 'notes.user']);
        
        // Filtrar por país si el usuario es "Master Pais"
        if (auth()->user()->hasRole('Master Pais')) {
            $query->where('ctry_id', auth()->user()->usr_id_ctry);
        }
        // Filtrar por leads asignados al Agente si tiene ese rol
        elseif (auth()->user()->hasRole('Agente Inmobiliario')) {
            $query->where('user_id', auth()->user()->id);
        }
        
        $leads = $query->paginate(10);
        
        // Filtrar países: Master Pais solo ve su país
        $countriesQuery = Country::select('ctry_id', 'ctry_name');
        if (auth()->user()->hasRole('Master Pais')) {
            $countriesQuery->where('ctry_id', auth()->user()->usr_id_ctry);
        }
        $countries = $countriesQuery->get();
        
        $developments = Development::select('devt_id', 'devt_title')->get();
        $sources = LeadSource::select('leadSou_id', 'leadSou_name')->get();
        $statuses = LeadStatus::select('leadSta_id', 'leadSta_name')->get();
        
        // Filtrar usuarios: Master Pais puede verse a sí mismo + agentes, Agente solo se ve a sí mismo
        $usersQuery = User::select('id', 'name', 'lastname');
        
        if (auth()->user()->hasRole('Master Pais')) {
            // Master País: su propio ID + agentes del país
            $usersQuery->where(function ($q) {
                $q->where('id', auth()->user()->id)
                  ->orWhereHas('roles', function ($roleQuery) {
                      $roleQuery->where('name', 'Agente Inmobiliario');
                  })->where('usr_id_ctry', auth()->user()->usr_id_ctry);
            });
        } elseif (auth()->user()->hasRole('Agente Inmobiliario')) {
            $usersQuery->where('id', auth()->user()->id);
        } else {
            // Admin/DevAdmin ven todos los agentes
            $usersQuery->whereHas('roles', function ($query) {
                $query->where('name', 'Agente Inmobiliario');
            });
        }
        $users = $usersQuery->get();

        return Inertia::render('Lead/Index', [
            'leads' => $leads,
            'countries' => $countries,
            'developments' => $developments,
            'sources' => $sources,
            'statuses' => $statuses,
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $developments = Development::select('devt_id', 'devt_title')->get();
        
        // Filtrar países: Master Pais solo ve su país
        $countriesQuery = Country::select('ctry_id', 'ctry_name');
        if (auth()->user()->hasRole('Master Pais')) {
            $countriesQuery->where('ctry_id', auth()->user()->usr_id_ctry);
        }
        $countries = $countriesQuery->get();
        
        $sources = LeadSource::select('leadSou_id', 'leadSou_name')->get();
        $statuses = LeadStatus::select('leadSta_id', 'leadSta_name')->get();
        
        // Filtrar usuarios: Master Pais puede verse a sí mismo + agentes, Agente solo se ve a sí mismo
        $usersQuery = User::select('id', 'name', 'lastname');
        
        if (auth()->user()->hasRole('Master Pais')) {
            // Master País: su propio ID + agentes del país
            $usersQuery->where(function ($q) {
                $q->where('id', auth()->user()->id)
                  ->orWhereHas('roles', function ($roleQuery) {
                      $roleQuery->where('name', 'Agente Inmobiliario');
                  })->where('usr_id_ctry', auth()->user()->usr_id_ctry);
            });
        } elseif (auth()->user()->hasRole('Agente Inmobiliario')) {
            // Agente solo ve su propio usuario
            $usersQuery->where('id', auth()->user()->id);
        } else {
            // Admin/DevAdmin ven todos los agentes
            $usersQuery->whereHas('roles', function ($query) {
                $query->where('name', 'Agente Inmobiliario');
            });
        }
        $users = $usersQuery->get();

        return Inertia::render('Lead/Create', [
            'developments' => $developments,
            'countries' => $countries,
            'sources' => $sources,
            'statuses' => $statuses,
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_client_name' => 'required|string|max:255',
            'lead_client_email' => 'nullable|email|max:255',
            'lead_client_phone' => 'nullable|string|max:20',
            'lead_message' => 'nullable|string',
            'lead_language' => 'nullable|string|max:10',
            'devt_id' => 'nullable|exists:developments,devt_id',
            'ctry_id' => 'nullable|exists:countries,ctry_id',
            'user_id' => 'nullable|exists:users,id',
            'leadSou_id' => 'nullable|exists:lead_sources,leadSou_id',
            'leadSta_id' => 'nullable|exists:lead_statuses,leadSta_id',
        ]);

        // Si el usuario es Agente Inmobiliario, asignar el lead a sí mismo
        if (auth()->user()->hasRole('Agente Inmobiliario')) {
            $validated['user_id'] = auth()->user()->id;
        }

        Lead::create($validated);

        return redirect()->route('lead.index')
            ->with('success', 'Lead creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        $lead->load(['development', 'country', 'source', 'status', 'user']);

        return Inertia::render('Lead/Show', [
            'lead' => $lead,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead)
    {
        // Verificar que el Agente Inmobiliario solo pueda editar sus propios leads
        if (auth()->user()->hasRole('Agente Inmobiliario') && $lead->user_id !== auth()->user()->id) {
            abort(403, 'No autorizado');
        }

        $lead->load(['development', 'country', 'source', 'status', 'user']);

        $developments = Development::select('devt_id', 'devt_title')->get();
        
        // Filtrar países: Master Pais solo ve su país
        $countriesQuery = Country::select('ctry_id', 'ctry_name');
        if (auth()->user()->hasRole('Master Pais')) {
            $countriesQuery->where('ctry_id', auth()->user()->usr_id_ctry);
        }
        $countries = $countriesQuery->get();
        
        $sources = LeadSource::select('leadSou_id', 'leadSou_name')->get();
        $statuses = LeadStatus::select('leadSta_id', 'leadSta_name')->get();
        
        // Filtrar usuarios: Master Pais puede verse a sí mismo + agentes, Agente solo se ve a sí mismo
        $usersQuery = User::select('id', 'name', 'lastname');
        
        if (auth()->user()->hasRole('Master Pais')) {
            // Master País: su propio ID + agentes del país
            $usersQuery->where(function ($q) {
                $q->where('id', auth()->user()->id)
                  ->orWhereHas('roles', function ($roleQuery) {
                      $roleQuery->where('name', 'Agente Inmobiliario');
                  })->where('usr_id_ctry', auth()->user()->usr_id_ctry);
            });
        } elseif (auth()->user()->hasRole('Agente Inmobiliario')) {
            // Agente solo ve su propio usuario
            $usersQuery->where('id', auth()->user()->id);
        } else {
            // Admin/DevAdmin ven todos los agentes
            $usersQuery->whereHas('roles', function ($query) {
                $query->where('name', 'Agente Inmobiliario');
            });
        }
        $users = $usersQuery->get();

        return Inertia::render('Lead/Edit', [
            'lead' => $lead,
            'developments' => $developments,
            'countries' => $countries,
            'sources' => $sources,
            'statuses' => $statuses,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        // Verificar que el Agente Inmobiliario solo pueda editar sus propios leads
        if (auth()->user()->hasRole('Agente Inmobiliario') && $lead->user_id !== auth()->user()->id) {
            abort(403, 'No autorizado');
        }

        $validated = $request->validate([
            'lead_client_name' => 'required|string|max:255',
            'lead_client_email' => 'nullable|email|max:255',
            'lead_client_phone' => 'nullable|string|max:20',
            'lead_message' => 'nullable|string',
            'lead_language' => 'nullable|string|max:10',
            'devt_id' => 'nullable|exists:developments,devt_id',
            'ctry_id' => 'nullable|exists:countries,ctry_id',
            'user_id' => 'nullable|exists:users,id',
            'leadSou_id' => 'nullable|exists:lead_sources,leadSou_id',
            'leadSta_id' => 'nullable|exists:lead_statuses,leadSta_id',
        ]);

        // Si el usuario es Agente Inmobiliario, mantener su ID
        if (auth()->user()->hasRole('Agente Inmobiliario')) {
            $validated['user_id'] = auth()->user()->id;
        }

        $lead->update($validated);

        return redirect()->route('lead.index')
            ->with('success', 'Lead actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        // Verificar autorización para Agente Inmobiliario
        if (auth()->user()->hasRole('Agente Inmobiliario') && $lead->user_id !== auth()->user()->id) {
            abort(403, 'No autorizado');
        }

        $lead->delete();

        return redirect()->route('lead.index')
            ->with('success', 'Lead eliminado exitosamente');
    }
}
