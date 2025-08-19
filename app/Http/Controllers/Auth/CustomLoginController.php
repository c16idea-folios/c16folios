<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class CustomLoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $remember = $request->has('remember'); // Devuelve true si la casilla está marcada

        if (Auth::attempt($credentials,$remember)) {
            $user = User::find(Auth::user()->id);


            if (!$user->is_active) {
                Auth::logout();
                return redirect()->route('login')->withErrors(['message' => 'Tu cuenta está inactiva.']);
            }

            // Redirige según el rol del usuario
            if ($user->hasRole('administrator')) {
                return redirect()->route('dashboard.admin');
            }

            if ($user->hasRole('operator')) {
               // return redirect()->route('dashboard.operator');
               return redirect()->route('dashboard.admin');

            }

            if ($user->hasRole('technical_support')) {
                //return redirect()->route('dashboard.support');
                return redirect()->route('dashboard.admin');

            }

            // Si no tiene un rol válido
            Auth::logout();
            return redirect()->route('login')->withErrors(['message' => 'Rol no autorizado.']);
        }

        return redirect()->route('login')->withErrors(['message' => 'Credenciales incorrectas.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
