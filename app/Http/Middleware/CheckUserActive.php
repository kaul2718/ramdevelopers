<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Si el usuario está autenticado pero inactivo, desconectarlo
        if (auth()->check() && !auth()->user()->usr_active) {
            Auth::guard('web')->logout();
            
            // Regenerar token ANTES de hacer flash
            $request->session()->regenerateToken();
            
            // Usar flash para pasar datos a Inertia en el redirect
            $request->session()->flash('error', 'Tu cuenta ha sido desactivada. Contacta al administrador.');
            
            return redirect(route('login'));
        }

        return $next($request);
    }
}



