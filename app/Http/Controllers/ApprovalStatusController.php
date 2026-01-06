<?php
// filepath: app/Http/Controllers/ApprovalStatusController.php

namespace App\Http\Controllers;

use App\Http\Requests\ApprovalStatusRequest;
use App\Models\ApprovalStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApprovalStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $approval_statuses = ApprovalStatus::latest()->paginate(10);
        return Inertia::render('ApprovalStatus/Index', ['approval_statuses' => $approval_statuses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('ApprovalStatus/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ApprovalStatusRequest $request)
    {
        ApprovalStatus::create($request->validated());
        return redirect()->route('approvalstatus.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $apvSta_id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApprovalStatus $approvalstatus)
    {
        return Inertia::render('ApprovalStatus/Edit', [
            'approval_status' => $approvalstatus,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ApprovalStatusRequest $request, ApprovalStatus $approvalstatus)
    {
        $approvalstatus->update($request->validated());
        return redirect()->route('approvalstatus.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApprovalStatus $approvalstatus)
    {
        $approvalstatus->delete();
        return redirect()->route('approvalstatus.index');
    }
}
