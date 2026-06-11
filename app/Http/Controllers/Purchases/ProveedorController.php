<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Http\Requests\Proveedor\StoreProveedorRequest;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $titulo = "Administrar Proveedores";
        $proveedores = Proveedor::latest()->get();
        return view('modules.proveedores.index', compact('proveedores', 'titulo'));
    }

    public function store(StoreProveedorRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        Proveedor::create($data);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor registrado correctamente.');
    }

    public function update(StoreProveedorRequest $request, Proveedor $proveedore)
    {
        $proveedore->update($request->validated());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedore)
    {

        if ($proveedore->compras()->exists()) {
            return to_route('proveedores.index')
                ->with('error', 'No se puede eliminar un proveedor con historial de compras.');
        }

        $proveedore->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado lógicamente.');
    }

    public function cambiarEstado(Proveedor $proveedore)
    {
        $proveedore->estado = !$proveedore->estado;
        $proveedore->save();

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}
