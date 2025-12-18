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
        $leads = Lead::with(['development', 'country', 'source', 'status', 'user'])
            ->paginate(10);

        return Inertia::render('Lead/Index', [
            'leads' => $leads,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $developments = Development::select('devt_id', 'devt_title')->get();
        $countries = Country::select('ctry_id', 'ctry_name')->get();
        $sources = LeadSource::select('leadSou_id', 'leadSou_name')->get();
        $statuses = LeadStatus::select('leadSta_id', 'leadSta_name')->get();
        $users = User::select('id', 'name')->get();

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
        $lead->load(['development', 'country', 'source', 'status', 'user']);

        $developments = Development::select('devt_id', 'devt_title')->get();
        $countries = Country::select('ctry_id', 'ctry_name')->get();
        $sources = LeadSource::select('leadSou_id', 'leadSou_name')->get();
        $statuses = LeadStatus::select('leadSta_id', 'leadSta_name')->get();
        $users = User::select('id', 'name')->get();

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

        $lead->update($validated);

        return redirect()->route('lead.index')
            ->with('success', 'Lead actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('lead.index')
            ->with('success', 'Lead eliminado exitosamente');
    }
}
