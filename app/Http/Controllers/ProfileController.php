<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Muestra el perfil del usuario autenticado.
     */
    public function edit()
    {
        $titulo = "Mi Perfil";
        $user = auth()->user();
        return view('modules.profile.edit', compact('user', 'titulo'));
    }

    /**
     * Actualiza los datos del perfil, avatar y contraseña de forma segura.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'avatar.image' => 'El archivo seleccionado debe ser una imagen válida.',
            'avatar.mimes' => 'La foto debe ser en formato JPG, JPEG o PNG.',
            'avatar.max' => 'La imagen no debe pesar más de 2MB.',
            'current_password.required_with' => 'Ingresa tu contraseña actual para cambiarla.',
            'new_password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
        ]);

        // Variable de control para monitorear cambios reales
        $cambioEfectuado = false;

        // A. Detectar cambio de foto
        if ($request->hasFile('avatar')) {
            if ($user->avatar && $user->avatar !== 'avatars/default.png' && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            $cambioEfectuado = true;
        }

        // B. Detectar cambio de nombre
        if ($user->name !== $request->name) {
            $user->name = $request->name;
            $cambioEfectuado = true;
        }

        // C. Detectar cambio de contraseña
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.'])->withInput();
            }

            $user->password = Hash::make($request->new_password);
            $cambioEfectuado = true;
        }

        // 🔥 Si no se detectó ningún cambio en A, B o C
        if (!$cambioEfectuado) {
            return redirect()->route('profile.edit')
                ->with('info', 'No se realizaron modificaciones en tu perfil.');
        }

        $user->save();

        return redirect()->route('profile.edit')
            ->with('success', '¡Perfil actualizado correctamente!');
    }
}
