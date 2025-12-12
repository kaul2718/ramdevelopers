<?php
namespace App\Http\Controllers;

use App\Http\Requests\ApprovalStatusRequest;
use App\Models\ApprovalStatus;
use Illuminate\Http\Request;
use inertia\Response;

class ApprovalStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $approval_statuses = ApprovalStatus::latest()->paginate(10);
        return inertia('ApprovalStatus/Index', ['approval_statuses' => $approval_statuses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('ApprovalStatus/Create');
    }

    /**
     * Store a newly created resource in storage.
     * @param App\Http\Request\ApprovalStatusRequest
     * @return \Illuminate\Http\Response
     */

    public function store(ApprovalStatusRequest $request)
    {
        ApprovalStatus::create(attributes: $request->validated());
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
    // En tu método edit del controller, agrega temporalmente:
    public function edit(ApprovalStatus $approvalstatus)
    {
        return inertia('ApprovalStatus/Edit', [
            'approval_status' => $approvalstatus,
        ]);
    }
    /**
     * Update the specified resource in storage.
     * @param App\Http\Request\ApprovalStatusRequest
     * @param int apvSta_id
     * @return \Illuminate\Http\Response
     */
    public function update(ApprovalStatusRequest $request, ApprovalStatus $approvalstatus)
    {
        $approvalstatus->update($request->validated());
        return redirect()->route('approvalstatus.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param ApprovalStatus $approvalstatus
     */
    public function destroy(ApprovalStatus $approvalstatus)
    {
        $approvalstatus -> delete();
        return redirect()->route('approvalstatus.index');
    }
}
