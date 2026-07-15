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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
        $clientes = Cliente::oldest()->get();
        $metodosPago = MetodoPago::orderBy('nombre')->get();
        $productos = Producto::where('estado', true)->where('stock', '>', 0)->orderBy('nombre')->get();

        return view('modules.ventas.create', compact('titulo', 'clientes', 'metodosPago', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VentaRequest $request)
    {
        try {
            // Ejecutamos el registro con los datos ya validados por tu FormRequest
            $venta = $this->ventaService->registrar(
                $request->validated(),
                auth()->id()
            );

            return redirect()
                ->route('ventas.index')
                ->with('success', 'Venta registrada correctamente. Comprobante: ' . $venta->numero_comprobante);
        } catch (\InvalidArgumentException $e) {
            // 🚨 Atrapamos la regla SUNAT que rebotó en el Service
            return back()
                ->withErrors(['cliente_id' => $e->getMessage()]) // Pintamos el mensaje en la vista
                ->withInput(); // Mantenemos el carrito y los campos llenos

        } catch (\Exception $e) {
            // 💥 Atrapamos cualquier otro error inesperado (Stock, Base de datos, etc.)
            Log::error("Error en venta: " . $e->getMessage());

            return back()
                ->withErrors(['error_general' => 'Ocurrió un problema interno. Inténtelo nuevamente.'])
                ->withInput();
        }
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

    public function storeOffline(Request $request)
    {
        try {
            Log::info("PWA API: Datos recibidos de IndexedDB:", $request->all());

            // 1. Validación manual calcada al 100% de tu VentaRequest
            $validador = Validator::make($request->all(), [
                'cliente_id'                  => 'nullable|exists:clientes,id',
                'metodo_pago_id'              => 'required|exists:metodos_pago,id',
                'tipo_comprobante'            => 'required|string|in:ticket,boleta,factura',
                'subtotal'                    => 'required|numeric|min:0',
                'igv'                         => 'required|numeric|min:0',
                'total'                       => 'required|numeric|min:0.01',
                'descuento'                   => 'nullable|numeric|min:0',
                'monto_recibido'              => 'nullable|numeric|min:0',
                'vuelto'                      => 'nullable|numeric|min:0',
                'productos'                   => 'required|array|min:1',
                'productos.*.id'              => 'required|exists:productos,id',
                'productos.*.cantidad'        => 'required|integer|min:1',
                'productos.*.precio_unitario' => 'required|numeric|min:0',
            ]);

            if ($validador->fails()) {
                Log::warn("PWA API: Falló la validación estricta:", $validador->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validación de venta fallida.',
                    'errors'  => $validador->errors()
                ], 422);
            }

            $datosValidados = $validador->validated();

            // 2. Adaptamos la estructura para que tu VentaService la digiera como si viniera del Formulario Web
            $datosParaService = [
                'cliente_id'       => $datosValidados['cliente_id'],
                'metodo_pago_id'   => $datosValidados['metodo_pago_id'],
                'tipo_comprobante' => $datosValidados['tipo_comprobante'],
                'subtotal'         => $datosValidados['subtotal'],
                'igv'              => $datosValidados['igv'],
                'total'            => $datosValidados['total'],
                'descuento'        => $datosValidados['descuento'] ?? 0,
                'monto_recibido'   => $datosValidados['monto_recibido'] ?? $datosValidados['total'],
                'vuelto'           => $datosValidados['vuelto'] ?? 0,

                // 🔥 Mapeo secuencial exacto para evitar conflictos de offsets en arrays
                'productos'        => collect($datosValidados['productos'])->map(function ($prod) {
                    return [
                        'id'              => (int) $prod['id'],
                        'cantidad'        => (int) $prod['cantidad'],
                        'precio_unitario' => (float) $prod['precio_unitario']
                    ];
                })->all()
            ];

            foreach ($datosValidados['productos'] as $prod) {
                $datosParaService['productos'][]  = $prod['id'];
                $datosParaService['cantidades'][] = $prod['cantidad'];
                $datosParaService['precios'][]    = $prod['precio_unitario'];
            }

            // 3. Ejecutar el VentaService
            $userId = auth()->id() ?? 1; // Cajero/Admin por defecto

            $venta = $this->ventaService->registrar($datosParaService, $userId);

            Log::info("PWA API: ¡ÉXITO! Venta guardada en base de datos. ID: {$venta->id}");

            return response()->json([
                'success' => true,
                'message' => 'Venta offline sincronizada correctamente.',
                'venta_id' => $venta->id
            ], 201);
        } catch (\InvalidArgumentException $e) {
            Log::warn("PWA API: Regla SUNAT rebotada en Service: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error SUNAT: ' . $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error("PWA API: Fallo de ejecución en Service: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error de servidor: ' . $e->getMessage()
            ], 500);
        }
    }
}
