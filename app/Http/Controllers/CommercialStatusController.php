<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommercialStatusRequest;
use App\Models\CommercialStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommercialStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commercial_statuses = CommercialStatus::latest()->paginate(10);
        return inertia('CommercialStatus/Index', ['commercial_statuses' => $commercial_statuses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('CommercialStatus/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommercialStatusRequest $request)
    {
        CommercialStatus::create($request->validated());
        return redirect()->route('commercialstatus.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $commSta_id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CommercialStatus $commercialstatus)
    {
        return Inertia::render('CommercialStatus/Edit', [
            'commercial_status' => $commercialstatus,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommercialStatusRequest $request, CommercialStatus $commercialstatus)
    {
        $commercialstatus->update($request->validated());
        return redirect()->route('commercialstatus.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommercialStatus $commercialstatus)
    {
        $commercialstatus->delete();
        return redirect()->route('commercialstatus.index');
    }
}
