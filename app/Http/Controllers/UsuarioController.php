<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Administrar Usuarios';
        $usuarios = User::with('roles')->latest()->get();
        return view("modules.usuarios.index", compact('titulo', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validateWithBag('store', [
            'store_name' => 'required|string|max:100',
            'store_email' => 'required|email|max:200|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name'
        ]);


        $user = User::create([
            'name' => trim($validated['store_name']),
            'email' => trim($validated['store_email']),
            'password' => Hash::make($validated['password']),
            'activo' => true
        ]);

        $user->syncRoles(
            $validated['role']
        );

        return to_route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $validated = $request->validateWithBag('update', [
            'edit_name' => 'required|string|max:100',
            'edit_email' => 'required|email|max:200|unique:users,email,' . $usuario->id,
            'edit_role' => 'required|exists:roles,name'
        ]);

        $usuario->name = trim($validated['edit_name']);
        $usuario->email = trim($validated['edit_email']);

        $roleActual = $usuario
            ->getRoleNames()
            ->first();

        $sinCambiosUsuario = !$usuario->isDirty();

        $sinCambiosRol = (
            $roleActual === $validated['edit_role']
        );

        if (
            $sinCambiosUsuario &&
            $sinCambiosRol
        ) {

            return back()->with(
                'info',
                'No se realizaron cambios.'
            );
        }

        $usuario->save();

        if (
            $usuario->id === auth()->id()
        ) {

            return back()->with(
                'error',
                'No puedes cambiar tu propio rol.'
            );
        }

        $usuario->syncRoles(
            $validated['edit_role']
        );

        return back()->with('success', 'Usuario actualizado correctamente.');
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

    public function roles_accesos()
    {
        $titulo = 'Roles';
        // Traemos los roles reales de tu base de datos (Admin, Cajero, Almacen)
        $roles = Role::all();

        // Definimos la matriz estática basada en tus reglas de negocio para la licorería
        $matrizModulos = [
            'Dashboard'           => ['Admin' => '✅', 'Cajero' => '✅', 'Almacen' => '✅'],
            'Clientes'            => ['Admin' => 'CRUD', 'Cajero' => 'C/E/A', 'Almacen' => '❌'],
            'Productos'           => ['Admin' => 'CRUD', 'Cajero' => 'Ver', 'Almacen' => 'C/E/A'],
            'Categorías'          => ['Admin' => 'CRUD', 'Cajero' => '❌', 'Almacen' => 'C/E/A'],
            'Marcas'              => ['Admin' => 'CRUD', 'Cajero' => '❌', 'Almacen' => 'C/E/A'],
            'Ventas'              => ['Admin' => 'CRUD', 'Cajero' => 'CRUD', 'Almacen' => '❌'],
            'Compras'             => ['Admin' => 'CRUD', 'Cajero' => '❌', 'Almacen' => 'CRUD'],
            'Proveedores'         => ['Admin' => 'CRUD', 'Cajero' => '❌', 'Almacen' => 'C/E/A'],
            'Usuarios'            => ['Admin' => 'CRUD', 'Cajero' => '❌', 'Almacen' => '❌'],
            'Reportes Ventas'     => ['Admin' => '✅', 'Cajero' => '❌', 'Almacen' => '❌'],
            'Reportes Inventario' => ['Admin' => '✅', 'Cajero' => '❌', 'Almacen' => '✅'],
            'Reportes Compras'    => ['Admin' => '✅', 'Cajero' => '❌', 'Almacen' => '❌'],
        ];

        return view('modules.roles.index', compact('roles', 'matrizModulos', 'titulo'));
    }
}
