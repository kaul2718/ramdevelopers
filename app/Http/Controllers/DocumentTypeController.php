<?php
// filepath: app/Http/Controllers/DocumentTypeController.php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentTypeRequest;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $document_types = DocumentType::latest()->paginate(10);
        return Inertia::render('DocumentType/Index', ['document_types' => $document_types]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('DocumentType/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DocumentTypeRequest $request)
    {
        DocumentType::create($request->validated());
        return redirect()->route('documenttype.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $docTyp_id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentType $documenttype)
    {
        return Inertia::render('DocumentType/Edit', [
            'document_type' => $documenttype,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DocumentTypeRequest $request, DocumentType $documenttype)
    {
        $documenttype->update($request->validated());
        return redirect()->route('documenttype.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentType $documenttype)
    {
        $documenttype->delete();
        return redirect()->route('documenttype.index');
    }
}