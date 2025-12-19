<?php

namespace App\Http\Controllers;

use App\Models\LeadNote;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeadNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leadNotes = LeadNote::with(['lead', 'user'])
            ->orderBy('leadNot_id', 'desc')
            ->paginate(10);

        return Inertia::render('LeadNote/Index', [
            'leadNotes' => $leadNotes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('LeadNote/Create', [
            'leads' => Lead::all(),
            'users' => User::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'leadNot_title' => 'nullable|string|max:150',
            'leadNot_description' => 'nullable|string',
            'leadNot_active' => 'nullable|boolean',
            'lead_id' => 'required|exists:leads,lead_id',
            'user_id' => 'required|exists:users,id',
        ]);

        // Por defecto, la nota se crea activa
        $validated['leadNot_active'] = true;

        LeadNote::create($validated);

        return redirect()->route('leadnote.index')
            ->with('message', 'Nota de Lead creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LeadNote $leadnote)
    {
        $leadnote->load(['lead', 'user']);

        return Inertia::render('LeadNote/Show', [
            'leadNote' => $leadnote,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeadNote $leadnote)
    {
        $leadnote->load(['lead', 'user']);

        return Inertia::render('LeadNote/Edit', [
            'leadNote' => $leadnote,
            'leads' => Lead::all(),
            'users' => User::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeadNote $leadnote)
    {
        $validated = $request->validate([
            'leadNot_title' => 'nullable|string|max:150',
            'leadNot_description' => 'nullable|string',
            'leadNot_active' => 'nullable|boolean',
            'lead_id' => 'required|exists:leads,lead_id',
            'user_id' => 'required|exists:users,id',
        ]);

        $leadnote->update($validated);

        return redirect()->route('leadnote.index')
            ->with('message', 'Nota de Lead actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeadNote $leadnote)
    {
        $leadnote->delete();

        return redirect()->route('leadnote.index')
            ->with('message', 'Nota de Lead eliminada exitosamente.');
    }
}
