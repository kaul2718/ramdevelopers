<?php
// filepath: app/Http/Controllers/LeadStatusController.php

namespace App\Http\Controllers;

use App\Http\Requests\LeadStatusRequest;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeadStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lead_statuses = LeadStatus::latest()->paginate(10);
        return Inertia::render('LeadStatus/Index', ['lead_statuses' => $lead_statuses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('LeadStatus/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeadStatusRequest $request)
    {
        LeadStatus::create($request->validated());
        return redirect()->route('leadstatus.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $leadSta_id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeadStatus $leadstatus)
    {
        return Inertia::render('LeadStatus/Edit', [
            'lead_status' => $leadstatus,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LeadStatusRequest $request, LeadStatus $leadstatus)
    {
        $leadstatus->update($request->validated());
        return redirect()->route('leadstatus.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeadStatus $leadstatus)
    {
        $leadstatus->delete();
        return redirect()->route('leadstatus.index');
    }
}