<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
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
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
