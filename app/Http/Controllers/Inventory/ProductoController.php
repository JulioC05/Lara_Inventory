<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Producto\StoreProductoRequest;
use App\Http\Requests\Producto\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Administrar Productos';
        $productos = Producto::with(['categoria', 'marca', 'user'])->latest()->get();
        $categorias = Categoria::where('estado', true)->get();
        $marcas = Marca::where('estado', true)->get();

        return view('modules.productos.index', compact(
            'productos',
            'categorias',
            'marcas',
            'titulo'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('imagen')) {

            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        } else {
            $validated['imagen'] = 'productos/default-product.jpg';
        }

        $validated['user_id'] = $request->user()->id;

        // Calcular margen automáticamente
        if ($validated['precio_compra'] > 0) {

            $validated['margen_ganancia'] =
                (
                    ($validated['precio_venta'] - $validated['precio_compra'])
                    / $validated['precio_compra']
                ) * 100;
        } else {

            $validated['margen_ganancia'] = 0;
        }

        Producto::create($validated);

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto registrado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $validated = $request->validated();
        /*
    |--------------------------------------------------------------------------
    | NUEVA IMAGEN
    |--------------------------------------------------------------------------
    */
        if ($request->hasFile('imagen')) {

            // eliminar anterior
            if ($producto->imagen) {

                Storage::disk('public')
                    ->delete($producto->imagen);
            }

            // guardar nueva
            $validated['imagen'] =
                $request->file('imagen')
                ->store('productos', 'public');
        }

        /*
    |--------------------------------------------------------------------------
    | RECALCULAR MARGEN
    |--------------------------------------------------------------------------
    */
        if ($validated['precio_compra'] > 0) {

            $validated['margen_ganancia'] = round(
                (
                    ($validated['precio_venta']
                        - $validated['precio_compra'])
                    / $validated['precio_compra']
                ) * 100,
                2
            );
        } else {

            $validated['margen_ganancia'] = 0;
        }

        /*
    |--------------------------------------------------------------------------
    | DETECTAR CAMBIOS
    |--------------------------------------------------------------------------
    */

        $producto->fill($validated);

        if (!$producto->isDirty()) {

            return redirect()
                ->route('productos.index')
                ->with(
                    'info',
                    'No se realizaron cambios.'
                );
        }
        /*
    |--------------------------------------------------------------------------
    | GUARDAR
    |--------------------------------------------------------------------------
    */
        $producto->save();
        return redirect()
            ->route('productos.index')
            ->with(
                'success',
                'Producto actualizado correctamente.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    public function cambiarEstado(Producto $producto)
    {
        $producto->estado = !$producto->estado;

        $producto->save();

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}
