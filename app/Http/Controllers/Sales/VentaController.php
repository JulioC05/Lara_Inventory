<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\Venta\VentaRequest;
use App\Models\Cliente;
use App\Models\MetodoPago;
use App\Models\MovimientoStock;
use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Administrar Ventas';
        $ventas = Venta::with(['cliente', 'usuario'])->latest()->get();
        return view('modules.ventas.index', compact('ventas', 'titulo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $titulo = 'Nueva Venta';
        $clientes = Cliente::orderBy('nombre')->get();
        $metodosPago = MetodoPago::orderBy('nombre')->get();
        $productos = Producto::where('estado', true)->where('stock', '>', 0)->orderBy('nombre')->get();

        return view('modules.ventas.create', compact('titulo', 'clientes', 'metodosPago', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VentaRequest $request)
    {

        DB::transaction(function () use ($request) {

            /*
        |--------------------------------------------------------------------------
        | CALCULO N° COMPROBANTE
        |--------------------------------------------------------------------------
        */
            $tipo = $request->tipo_comprobante;
            $serie = match ($tipo) {
                'boleta' => 'B001',
                'factura' => 'F001',
            };

            $ultimaVenta = Venta::where(
                'tipo_comprobante',
                $tipo
            )
                ->whereNotNull('numero_comprobante')
                ->latest('id')
                ->first();

            $ultimoNumero = 0;

            if ($ultimaVenta) {

                $partes = explode(
                    '-',
                    $ultimaVenta->numero_comprobante
                );

                $ultimoNumero = (int) $partes[1];
            }

            $nuevoNumero = str_pad(
                $ultimoNumero + 1,
                8,
                '0',
                STR_PAD_LEFT
            );

            $numeroComprobante = "{$serie}-{$nuevoNumero}";

            $venta = Venta::create([

                'cliente_id' => $request->cliente_id,
                'user_id' => auth()->id(),
                'metodo_pago_id' => $request->metodo_pago_id,
                'numero_comprobante' => $numeroComprobante,
                'tipo_comprobante' => $request->tipo_comprobante,
                'subtotal' => $request->subtotal,
                'igv' => $request->igv,
                'descuento' => $request->descuento ?? 0,
                'total' => $request->total,
                'monto_recibido' => $request->monto_recibido,
                'vuelto' => $request->vuelto ?? 0,
                'estado' => 'completada',
                'fecha_venta' => now(),
            ]);

            foreach ($request->productos as $item) {

                $producto = Producto::findOrFail($item['id']);

                /*
            |--------------------------------------------------------------------------
            | VALIDAR STOCK
            |--------------------------------------------------------------------------
            */

                if ($producto->stock < $item['cantidad']) {

                    throw new \Exception(
                        "Stock insuficiente para {$producto->nombre}"
                    );
                }

                /*
            |--------------------------------------------------------------------------
            | CALCULOS
            |--------------------------------------------------------------------------
            */

                $total = $item['precio_unitario'] * $item['cantidad'];

                $subtotal = $total / 1.18;

                $igv = $total - $subtotal;

                /*
            |--------------------------------------------------------------------------
            | DETALLE VENTA
            |--------------------------------------------------------------------------
            */

                $venta->detalles()->create([

                    'producto_id' => $producto->id,

                    'cantidad' => $item['cantidad'],

                    'precio_unitario' => $item['precio_unitario'],

                    'igv' => $igv,

                    'descuento' => 0,

                    'subtotal' => $subtotal,

                    'total' => $total,
                ]);

                /*
            |--------------------------------------------------------------------------
            | DESCONTAR STOCK
            |--------------------------------------------------------------------------
            */

                $stockAnterior = $producto->stock;

                $producto->decrement(
                    'stock',
                    $item['cantidad']
                );

                $stockNuevo = $producto->fresh()->stock;

                MovimientoStock::create([

                    'producto_id' => $producto->id,

                    'user_id' => auth()->id(),

                    'tipo_movimiento' => 'salida',

                    'motivo' => 'venta',

                    'referencia' => $venta->numero_comprobante,

                    'cantidad' => $item['cantidad'],

                    'stock_anterior' => $stockAnterior,

                    'stock_nuevo' => $stockNuevo,
                ]);
            }
        });

        return redirect()
            ->route('ventas.index')
            ->with('success', 'Venta registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {

        $titulo = 'Detalle de venta';

        $venta->load([
            'cliente',
            'usuario',
            'metodoPago',
            'detalles.producto'
        ]);

        return view('modules.ventas.show', compact('venta', 'titulo'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venta $venta)
    {
        /*
    |--------------------------------------------------------------------------
    | VALIDAR SI YA ESTA ANULADA
    |--------------------------------------------------------------------------
    */

        if ($venta->estado === 'anulada') {

            return redirect()
                ->back()
                ->with('error', 'La venta ya está anulada.');
        }

        DB::transaction(function () use ($venta) {

            /*
        |--------------------------------------------------------------------------
        | CARGAR RELACIONES
        |--------------------------------------------------------------------------
        */

            $venta->load('detalles.producto');

            /*
        |--------------------------------------------------------------------------
        | RECORRER DETALLES
        |--------------------------------------------------------------------------
        */

            foreach ($venta->detalles as $detalle) {

                $producto = $detalle->producto;

                /*
            |--------------------------------------------------------------------------
            | STOCK
            |--------------------------------------------------------------------------
            */

                $stockAnterior = $producto->stock;

                $stockNuevo = $stockAnterior + $detalle->cantidad;

                /*
            |--------------------------------------------------------------------------
            | DEVOLVER STOCK
            |--------------------------------------------------------------------------
            */

                $producto->increment(
                    'stock',
                    $detalle->cantidad
                );

                /*
            |--------------------------------------------------------------------------
            | REGISTRAR MOVIMIENTO
            |--------------------------------------------------------------------------
            */

                MovimientoStock::create([

                    'producto_id' => $producto->id,

                    'user_id' => auth()->id(),

                    'tipo_movimiento' => 'entrada',

                    'motivo' => 'anulacion',

                    'referencia' => $venta->numero_comprobante,

                    'cantidad' => $detalle->cantidad,

                    'stock_anterior' => $stockAnterior,

                    'stock_nuevo' => $stockNuevo,
                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | CAMBIAR ESTADO
        |--------------------------------------------------------------------------
        */

            $venta->update([
                'estado' => 'anulada'
            ]);
        });

        return redirect()
            ->route('ventas.index')
            ->with('success', 'Venta anulada correctamente.');
    }
}
