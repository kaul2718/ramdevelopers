<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusinessStateRequest;
use App\Models\BusinessState;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BusinessStateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $business_states = BusinessState::latest()->paginate(10);
        return inertia('BusinessState/Index', ['business_states' => $business_states]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('BusinessState/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BusinessStateRequest $request)
    {
        BusinessState::create($request->validated());
        return redirect()->route('businessstate.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $busiSta_id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BusinessState $businessstate)
    {
        return Inertia::render('BusinessState/Edit', [
            'business_state' => $businessstate,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BusinessStateRequest $request, BusinessState $businessstate)
    {
        $businessstate->update($request->validated());
        return redirect()->route('businessstate.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BusinessState $businessstate)
    {
        $businessstate->delete();
        return redirect()->route('businessstate.index');
    }
}
