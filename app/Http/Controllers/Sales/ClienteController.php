<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cliente\StoreClienteRequest;
use App\Http\Requests\Cliente\UpdateClienteRequest;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $titulo = "Administrar Clientes";
        $clientes = Cliente::latest()->get();
        // $buscar = $request->buscar;

        // $clientes = Cliente::query()
        //     ->when($buscar, function ($query, $buscar) {
        //         $query->where('numero_documento', 'like', "%{$buscar}%")
        //             ->orWhere('nombre', 'like', "%{$buscar}%")
        //             ->orWhere('apellido', 'like', "%{$buscar}%")
        //             ->orWhere('razon_social', 'like', "%{$buscar}%");
        //     })
        //     ->latest()
        //     ->paginate(10);

        return view('modules.clientes.index', compact('clientes', 'titulo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClienteRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = $request->user()->id;

        // PERSONA NATURAL
        if ($data['tipo_persona'] === 'natural') {

            $data['razon_social'] = null;
            $data['contacto_nombre'] = null;
            $data['contacto_cargo'] = null;
        }

        // PERSONA JURIDICA
        else {

            $data['nombre'] = null;
            $data['apellido'] = null;
        }

        $data['estado'] = true;

        Cliente::create($data);

        return to_route('clientes.index')
            ->with(
                'success',
                'Cliente registrado correctamente.'
            );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        $data = $request->validated();

        // =====================================
        // PERSONA NATURAL
        // =====================================

        if ($data['tipo_persona'] === 'natural') {

            // Limpiar datos jurídicos
            $data['razon_social'] = null;

            $data['contacto_nombre'] = null;

            $data['contacto_cargo'] = null;
        }

        // =====================================
        // PERSONA JURIDICA
        // =====================================

        else {

            // Limpiar datos naturales
            $data['nombre'] = null;

            $data['apellido'] = null;
        }

        // =====================================
        // UPDATE
        // =====================================

        $cliente->update($data);

        // =====================================
        // RESPONSE
        // =====================================

        return redirect()
            ->route('clientes.index')
            ->with(
                'success',
                'Cliente actualizado correctamente.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        // =====================================
        // VALIDAR VENTAS
        // =====================================

        if ($cliente->ventas()->exists()) {

            return redirect()
                ->route('clientes.index')
                ->with(
                    'error',
                    'No se puede eliminar un cliente con ventas registradas.'
                );
        }

        // =====================================
        // ELIMINAR
        // =====================================

        $cliente->delete();

        return redirect()
            ->route('clientes.index')
            ->with(
                'success',
                'Cliente eliminado correctamente.'
            );
    }

    public function cambiarEstado(Cliente $cliente)
    {
        $cliente->estado = !$cliente->estado;

        $cliente->save();

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}
