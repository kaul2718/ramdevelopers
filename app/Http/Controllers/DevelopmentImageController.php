<?php

namespace App\Http\Controllers;

use App\Models\Development;
use App\Models\DevelopmentImage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DevelopmentImageController extends Controller
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
        
        return Inertia::render('DevelopmentImage/Create', [
            'development' => $development,
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

        // Procesar imágenes - buscar en el request todos los que coincidan con el patrón images.*.file
        $index = 0;
        $order = 1;
        while ($request->hasFile("images.{$index}.file")) {
            $title = $request->input("images.{$index}.devImg_title");
            $isCover = $request->input("images.{$index}.devImg_is_cover");
            $file = $request->file("images.{$index}.file");

            if ($file && $file->isValid() && $title) {
                // Guardar imagen en storage
                $path = $file->store("developments/{$developmentId}/images", 'public');
                
                // Crear registro en BD
                DevelopmentImage::create([
                    'devt_id' => $developmentId,
                    'devImg_title' => $title,
                    'devImg_url' => $path,
                    'devImg_is_cover' => $isCover ? true : false,
                    'devImg_order' => $order++,
                ]);
            }
            $index++;
        }

        // Retornar respuesta exitosa
        return response()->json([
            'success' => true,
            'message' => 'Imágenes subidas correctamente. ¡Desarrollo creado!'
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
        //
    }
}
