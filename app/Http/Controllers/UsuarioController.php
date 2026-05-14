<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Administrar Usuarios';
        $items = User::latest()->get();
        return view("modules.usuarios.index", compact('titulo', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validateWithBag('store', [
            'store_name' => 'required|string|max:100',
            'store_email' => 'required|email|max:200|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ]);


        User::create([
            'name' => trim($validated['store_name']),
            'email' => trim($validated['store_email']),
            'password' => Hash::make($validated['password']),
            'activo' => true
        ]);

        return to_route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
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
    public function update(Request $request, User $usuario)
    {
        $validated = $request->validateWithBag('update', [
            'edit_name' => 'required|string|max:100',
            'edit_email' => 'required|email|max:200|unique:users,email,' . $usuario->id
        ]);

        $usuario->name = trim($validated['edit_name']);
        $usuario->email = trim($validated['edit_email']);

        if (!$usuario->isDirty()) {
            return back()->with(
                'info',
                'No se realizaron cambios.'
            );
        }

        $usuario->save();

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function cambiarEstado(User $usuario)
    {
        if (Auth::id() === $usuario->id) {
            return back()->with(
                'error',
                'No puedes desactivar tu propio usuario.'
            );
        }

        $usuario->activo = !$usuario->activo;

        $usuario->save();

        return back()->with(
            'success',
            'Estado actualizado correctamente.'
        );
    }
}
