<?php
// filepath: app/Http/Controllers/LeadSourcesController.php

namespace App\Http\Controllers;

use App\Http\Requests\LeadSourceRequest;
use App\Models\LeadSource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeadSourcesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lead_sources = LeadSource::latest()->paginate(10);
        return Inertia::render('LeadSource/Index', ['lead_sources' => $lead_sources]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('LeadSource/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeadSourceRequest $request)
    {
        LeadSource::create($request->validated());
        return redirect()->route('leadsources.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $leadSou_id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeadSource $leadsource)
    {
        return Inertia::render('LeadSource/Edit', [
            'lead_source' => $leadsource,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LeadSourceRequest $request, LeadSource $leadsource)
    {
        $leadsource->update($request->validated());
        return redirect()->route('leadsources.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeadSource $leadsource)
    {
        $leadsource->delete();
        return redirect()->route('leadsources.index');
    }
}