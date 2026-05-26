<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function index() {
        $titulo = "Login de usuarios";
        return view("modules.auth.login", compact("titulo"));
    }

    public function logear(Request $request) {
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

    if (!Auth::attempt($credenciales)) {
        RateLimiter::hit($key, 60);

        return back()->withErrors([
            'email' => 'Credenciales incorrectas'
        ])->onlyInput('email');
    }

        RateLimiter::clear($key);


        // $user = User::where('email', $request->email)->first();

        // if(!$user || !Hash::check($request->password, $user->password)){
        //     return back()->withErrors(['email' => 'Credencial incorrecta'])->withInput();
        // }

        // if(!$user->activo) {
        //     return back()->withErrors(['email' => 'Tu cuenta esta inactiva']);
        // }

        // Auth::login($user);
        $request->session()->regenerate();

        if (!Auth::user()->activo) {
        Auth::logout();
        return back()->withErrors([
            'email' => 'Cuenta inactiva'
        ]);
    }
        return to_route('dashboard');
    }

    public function crearAdmin(){
        User::create([
            'name' => 'John Doe',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'activo' => true,
            'rol' => 'admin'
        ]);

        return "admin creado con exito";
    }

    public function logout() {
        Auth::logout();
        return to_route('login');
    }
}
