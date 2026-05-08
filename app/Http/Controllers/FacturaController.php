<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\FacturaEnviada; // <-- Importa el Mailable arriba
use Illuminate\Support\Facades\Mail;

class FacturaController extends Controller
{
    // Mostrar la lista de facturas
    public function index()
    {
        // Traemos las facturas incluyendo los datos del cliente asociado
        $facturas = auth()->user()->facturas()->with('cliente')->latest()->get();
        return Inertia::render('Facturas/Index', ['facturas' => $facturas]);
    }

    // Mostrar el formulario para crear una nueva factura
    public function create()
    {
        // Le enviamos a Vue las listas para que puedas seleccionarlos en los select/dropdowns
        $clientes = auth()->user()->clientes;
        $productos = auth()->user()->productos;

        return Inertia::render('Facturas/Create', [
            'clientes' => $clientes,
            'productos' => $productos
        ]);
    }

    // Guardar la factura y sus detalles en la base de datos
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'detalles' => 'required|array|min:1', // Debe haber al menos 1 producto
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
        ]);

        // Iniciamos la transacción segura
        $factura = DB::transaction(function () use ($validated, $request) {
            $subtotalFactura = 0;

            // 1. Creamos la cabecera (Factura) vacía por ahora
            $factura = $request->user()->facturas()->create([
                'cliente_id' => $validated['cliente_id'],
                'estado' => 'Emitida',
                'fecha_emision' => now(),
            ]);

            // 2. Procesamos cada producto que agregaste a la factura
            foreach ($validated['detalles'] as $item) {
                $producto = Producto::find($item['producto_id']);
                $cantidad = $item['cantidad'];
                $subtotalLinea = $producto->precio * $cantidad;

                // Guardamos la línea de detalle
                $factura->detalles()->create([
                    'producto_id' => $producto->id,
                    'nombre_producto' => $producto->nombre, // Guardamos el nombre fijo histórico
                    'cantidad' => $cantidad,
                    'precio_unitario' => $producto->precio, // Guardamos el precio fijo histórico
                    'subtotal' => $subtotalLinea,
                ]);

                $subtotalFactura += $subtotalLinea;
            }

            // 3. Calculamos el IVA (19%) y actualizamos los totales de la cabecera
            $iva = (int) round($subtotalFactura * 0.19);
            $total = $subtotalFactura + $iva;

            $factura->update([
                // Generamos un número de folio bonito, ej: FAC-00001
                'numero' => 'FAC-' . str_pad($factura->id, 5, '0', STR_PAD_LEFT),
                'subtotal' => $subtotalFactura,
                'iva' => $iva,
                'total' => $total,
            ]);

            return $factura;
        });

        if ($factura->cliente->email) {
    Mail::to($factura->cliente->email)->send(new FacturaEnviada($factura));
}


        // Al terminar, volvemos a la lista de facturas
        return redirect()->route('facturas.index');
    }

    public function download(Factura $factura)
    {
        // Verificamos que la factura pertenezca al usuario logueado
        if ($factura->user_id !== auth()->id()) {
            abort(403);
        }

        // Cargamos la vista y le pasamos la factura con sus detalles
        $pdf = Pdf::loadView('pdf.factura', compact('factura'));

        // Descarga el archivo con un nombre descriptivo
        return $pdf->download("factura-{$factura->numero}.pdf");
    }

    public function destroy(Factura $factura)
{
    if ($factura->user_id !== auth()->id()) { abort(403); }
    $factura->delete();
    return redirect()->back();
}
}
