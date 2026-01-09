<?php

namespace App\Http\Controllers;

use App\Http\Requests\HousingTypeRequest;
use App\Models\HousingType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HousingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $housing_types = HousingType::latest()->paginate(10);
        return Inertia::render('HousingType/Index', ['housing_types' => $housing_types]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('HousingType/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HousingTypeRequest $request)
    {
        HousingType::create($request->validated());
        return redirect()->route('housingtype.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $houTyp_id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HousingType $housingtype)
    {
        return Inertia::render('HousingType/Edit', [
            'housing_type' => $housingtype,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HousingTypeRequest $request, HousingType $housingtype)
    {
        $housingtype->update($request->validated());
        return redirect()->route('housingtype.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HousingType $housingtype)
    {
        $housingtype->delete();
        return redirect()->route('housingtype.index');
    }
}
