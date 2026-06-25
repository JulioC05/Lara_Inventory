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
use App\Services\VentaService;
use Barryvdh\DomPDF\Facade\Pdf;

class VentaController extends Controller
{

    public function __construct(
        protected VentaService $ventaService
    ) {}

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
        $this->ventaService->registrar(
            $request->validated(),
            auth()->id()
        );

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

                    'motivo' => 'anulacion_venta',

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

    public function descargarPdf($id)
    {
        // Recuperamos la venta con todas sus relaciones cargadas de golpe
        $venta = Venta::with(['cliente', 'usuario', 'metodoPago', 'detalles.producto'])
            ->findOrFail($id);

        // Pasamos la data a una vista Blade exclusiva para el diseño del PDF
        $pdf = Pdf::loadView('modules.ventas.partials.pdf', compact('venta'));

        // Definimos el formato del papel (A4 estándar de escritorio)
        $pdf->setPaper('a4', 'portrait');

        // Formateamos el nombre del archivo: boleta_B001-00021.pdf
        $nombreArchivo = strtolower($venta->tipo_comprobante) . '_' . ($venta->numero_comprobante ?? 'sin_numero') . '.pdf';

        // Retornamos el flujo de descarga directa
        return $pdf->download($nombreArchivo);
    }
}
