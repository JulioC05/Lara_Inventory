<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Marca\StoreMarcaRequest;
use App\Http\Requests\Marca\UpdateMarcaRequest;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Administrar Marcas';
        $items = Marca::all();
        return view("modules.marcas.index", compact('titulo', 'items'));
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
    public function store(StoreMarcaRequest $request)
    {
        Marca::create([
            'user_id' => Auth::id(),
            'nombre' => trim($request['nombre']),
            'estado' => true
        ]);

        return to_route('marcas.index')
            ->with('success', 'Marca creada correctamente.');
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
    public function update(UpdateMarcaRequest $request, Marca $marca)
    {
        $marca->fill([
            'nombre' => trim($request['nombre']),
        ]);

        if (!$marca->isDirty()) {

            return back()->with(
                'info',
                'No se realizaron cambios.'
            );
        }

        $marca->save();

        return to_route('marcas.index')
            ->with('success', 'Marca actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marca $marca)
    {
        $marca->delete();

        return to_route('marcas.index')
            ->with('success', 'Marca eliminada correctamente.');
    }

    public function cambiarEstado(Marca $marca)
    {
        $marca->estado = !$marca->estado;

        $marca->save();

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}
