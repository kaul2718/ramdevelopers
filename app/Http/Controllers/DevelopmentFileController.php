<?php

namespace App\Http\Controllers;

use App\Models\Development;
use App\Models\DevelopmentFile;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DevelopmentFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $developmentId = $request->query('development_id');
        $development = Development::findOrFail($developmentId);
        $documentTypes = DocumentType::where('docTyp_active', true)->get();
        
        return Inertia::render('DevelopmentFile/Create', [
            'development' => $development,
            'documentTypes' => $documentTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $developmentId = $request->input('development_id');
        
        $request->validate([
            'development_id' => 'required|exists:developments,devt_id',
        ]);

        // Procesar archivos
        $index = 0;
        $filesProcessed = 0;
        while ($request->hasFile("files.{$index}.file")) {
            $docTypId = $request->input("files.{$index}.docTyp_id");
            $fileName = $request->input("files.{$index}.devFile_name");
            $file = $request->file("files.{$index}.file");

            if ($file && $file->isValid() && $docTypId && $fileName) {
                try {
                    // Guardar archivo en storage
                    $path = $file->store("developments/{$developmentId}/files", 'public');
                    
                    // Crear registro en BD
                    DevelopmentFile::create([
                        'devt_id' => $developmentId,
                        'docTyp_id' => $docTypId,
                        'devFile_name' => $fileName,
                        'devFile_url' => $path,
                        'devFile_isPublic' => true,
                        'user_id' => auth()->id(),
                    ]);
                    
                    $filesProcessed++;
                } catch (\Exception $e) {
                    \Log::error("Error storing file:", ['error' => $e->getMessage()]);
                }
            }
            $index++;
        }

        // Retornar respuesta exitosa
        return response()->json([
            'success' => true,
            'filesProcessed' => $filesProcessed,
            'message' => "Se procesaron {$filesProcessed} archivos correctamente."
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $file = DevelopmentFile::findOrFail($id);
        
        // Eliminar el archivo del almacenamiento
        if ($file->devFile_url && \Storage::exists($file->devFile_url)) {
            \Storage::delete($file->devFile_url);
        }
        
        // Eliminar el registro de la base de datos
        $file->delete();
        
        return response()->json(['message' => 'Archivo eliminado correctamente'], 200);
    }
}
