<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function index()
    {
        $titulo = "Login de usuarios";
        return view("modules.auth.login", compact("titulo"));
    }

    public function logear(Request $request)
    {
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        $key = 'login-' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors([
                'email' => 'Demasiados intentos. Intenta luego.'
            ]);
        }

        // 1. 🔥 Capturamos si el input 'remember' viene en la petición (el JS de la PWA lo mandará como "1")
        // Usamos filter_var para convertirlo en un booleano real (true/false) de forma segura
        $remember = $request->has('remember') ? filter_var($request->input('remember'), FILTER_VALIDATE_BOOLEAN) : false;

        // 2. 🔥 Pasamos $remember como segundo parámetro en el intento de autenticación
        if (!Auth::attempt($credenciales, $remember)) {
            RateLimiter::hit($key, 60);

            return back()->withErrors([
                'email' => 'Credenciales incorrectas'
            ])->onlyInput('email');
        }

        RateLimiter::clear($key);

        $request->session()->regenerate();

        if (!Auth::user()->activo) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Cuenta inactiva'
            ]);
        }

        return to_route('dashboard');
    }

    public function logout()
    {
        Auth::logout();
        return to_route('login');
    }
}
