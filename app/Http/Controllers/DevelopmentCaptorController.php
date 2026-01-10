<?php
// filepath: app/Http/Controllers/DevelopmentCaptorController.php

namespace App\Http\Controllers;

use App\Http\Requests\DevelopmentCaptorRequest;
use App\Models\Development;
use App\Models\DevelopmentCaptor;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DevelopmentCaptorController extends Controller
{
    /**
     * Display a listing of captors for a specific development.
     */
    public function index(Development $development)
    {
        $development->load(['developer', 'country', 'city', 'approvalStatus', 'businessStatus', 'commercialStatus', 'housingType', 'currency', 'files', 'images']);
        
        $captors = $development->developmentCaptors()
            ->with('user')
            ->get()
            ->map(function ($captor) {
                return [
                    'devt_id' => $captor->devt_id,
                    'user_id' => $captor->user_id,
                    'devtUsr_is_main' => $captor->devtUsr_is_main,
                    'created_at' => $captor->created_at,
                    'updated_at' => $captor->updated_at,
                    'user' => $captor->user ? [
                        'id' => $captor->user->id,
                        'name' => $captor->user->name,
                        'lastname' => $captor->user->lastname,
                        'email' => $captor->user->email,
                        'phone' => $captor->user->phone,
                    ] : null,
                ];
            });

        $availableUsers = User::where('usr_active', true)
            ->whereNotIn('id', $development->developmentCaptors()->pluck('user_id'))
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'lastname' => $user->lastname,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ];
            });

        return Inertia::render('Development/Captors/Index', [
            'development' => $development,
            'captors' => $captors,
            'availableUsers' => $availableUsers,
        ]);
    }

    /**
     * Store a new captor for a development (AJAX request).
     */
    public function store(DevelopmentCaptorRequest $request, Development $development)
    {
        $userId = $request->validated()['user_id'];
        $isMain = $request->validated()['devtUsr_is_main'] ?? false;

        // Verificar si el usuario ya es captador de este desarrollo
        $existingCaptor = DevelopmentCaptor::where('devt_id', $development->devt_id)
            ->where('user_id', $userId)
            ->first();

        if ($existingCaptor) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Este usuario ya es captador de este desarrollo.',
                    'data' => null,
                ], 409);
            }
            return back()->withErrors(['user_id' => 'Este usuario ya es captador de este desarrollo.']);
        }

        try {
            // Si es principal, desmarcr otros captadores principales
            if ($isMain) {
                DevelopmentCaptor::where('devt_id', $development->devt_id)
                    ->update(['devtUsr_is_main' => false]);
            }

            $captor = DevelopmentCaptor::create([
                'devt_id' => $development->devt_id,
                'user_id' => $userId,
                'devtUsr_is_main' => $isMain,
            ]);

            $captor->load('user');

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Captador asignado correctamente.',
                    'data' => $captor,
                ], 201);
            }

            return back()->with('success', 'Captador asignado correctamente.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Error al asignar el captador.',
                    'error' => $e->getMessage(),
                ], 500);
            }
            return back()->withErrors(['error' => 'Error al asignar el captador.']);
        }
    }

    /**
     * Display a specific captor assignment.
     */
    public function show(Development $development, $userId)
    {
        $captor = DevelopmentCaptor::where('devt_id', $development->devt_id)
            ->where('user_id', $userId)
            ->with('user')
            ->firstOrFail();

        if (request()->expectsJson()) {
            return response()->json([
                'data' => $captor,
                'message' => 'Captador obtenido correctamente.',
            ], 200);
        }

        return response()->json($captor);
    }

    /**
     * Update a captor assignment.
     */
    public function update(DevelopmentCaptorRequest $request, Development $development, $userId)
    {
        $captor = DevelopmentCaptor::where('devt_id', $development->devt_id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $validated = $request->validated();

        try {
            $captor->update($validated);
            $captor->load('user');

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Captador actualizado correctamente.',
                    'data' => $captor,
                ], 200);
            }

            return back()->with('success', 'Captador actualizado correctamente.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Error al actualizar el captador.',
                    'error' => $e->getMessage(),
                ], 500);
            }
            return back()->withErrors(['error' => 'Error al actualizar el captador.']);
        }
    }

    /**
     * Remove a captor assignment from a development.
     */
    public function destroy(Development $development, $userId)
    {
        $captor = DevelopmentCaptor::where('devt_id', $development->devt_id)
            ->where('user_id', $userId)
            ->firstOrFail();

        try {
            $captor->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Captador removido correctamente.',
                ], 200);
            }

            return back()->with('success', 'Captador removido correctamente.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Error al remover el captador.',
                    'error' => $e->getMessage(),
                ], 500);
            }
            return back()->withErrors(['error' => 'Error al remover el captador.']);
        }
    }

    /**
     * Set a captor as the main captor for a development.
     */
    public function setMain(Development $development, $userId)
    {
        $captor = DevelopmentCaptor::where('devt_id', $development->devt_id)
            ->where('user_id', $userId)
            ->firstOrFail();

        try {
            // Remover otros captadores principales
            DevelopmentCaptor::where('devt_id', $development->devt_id)
                ->where('user_id', '!=', $userId)
                ->update(['devtUsr_is_main' => false]);

            // Establecer como principal
            $captor->update(['devtUsr_is_main' => true]);
            $captor->load('user');

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Captador establecido como principal.',
                    'data' => $captor,
                ], 200);
            }

            return back()->with('success', 'Captador establecido como principal.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Error al establecer el captador como principal.',
                    'error' => $e->getMessage(),
                ], 500);
            }
            return back()->withErrors(['error' => 'Error al establecer como principal.']);
        }
    }

    /**
     * Get available users for a development (AJAX request).
     */
    public function getAvailableUsers(Development $development)
    {
        $assignedUserIds = DevelopmentCaptor::where('devt_id', $development->devt_id)
            ->pluck('user_id')
            ->toArray();

        $availableUsers = User::where('usr_active', true)
            ->whereNotIn('id', $assignedUserIds)
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'lastname' => $user->lastname,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ];
            });

        return response()->json([
            'data' => $availableUsers,
            'message' => 'Usuarios disponibles obtenidos correctamente.',
        ], 200);
    }
}
