<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    /**
     * Manejar una solicitud entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Verificar si el usuario está inactivo
        if ($user && !$user->is_active) {
            Auth::logout(); // Cerrar sesión si el usuario está inactivo

            return redirect()->route('login')->withErrors([
                'message' => 'Tu cuenta está inactiva. Contacta al administrador para más información.',
            ]);
        }

        return $next($request);
    }
}
