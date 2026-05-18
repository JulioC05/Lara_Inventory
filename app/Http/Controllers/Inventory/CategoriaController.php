<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categoria\StoreCategoriaRequest;
use App\Http\Requests\Categoria\UpdateCategoriaRequest;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Administrar Categorias';
        $items = Categoria::all();
        return view("modules.categorias.index", compact('titulo', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $titulo = 'Crear Categoria';
        return view("modules.categorias.create", compact('titulo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriaRequest $request)
    {
        Categoria::create([
            'user_id' => Auth::id(),
            'nombre' => trim($request['nombre']),
            'estado' => true
        ]);

        return to_route('categorias.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Categoria::find($id);
        return view('modules.categorias.show', compact('item'));
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
    public function update(UpdateCategoriaRequest $request, Categoria $categoria)
    {
        $categoria->fill([
            'nombre' => trim($request['nombre']),
        ]);

        if (!$categoria->isDirty()) {

            return back()->with(
                'info',
                'No se realizaron cambios.'
            );
        }

        $categoria->save();

        return to_route('categorias.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        return to_route('categorias.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }

    public function cambiarEstado(Categoria $categoria)
    {
        $categoria->estado = !$categoria->estado;

        $categoria->save();

        return back()->with('success','Estado actualizado correctamente.');
    }
}
