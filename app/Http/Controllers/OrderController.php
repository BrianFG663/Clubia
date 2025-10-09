<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    function obtenerDetalles($id)
    {
        $orden = Order::with('orderDetails', 'supplier')->findOrFail($id);

        return response()->json([
            'proveedor' => $orden->supplier->nombre, 
            'detalles' => $orden->orderDetails->map(function($detalle) {
                return [
                    'id' => $detalle->id,
                    'producto' => $detalle->nombre_producto,
                    'cantidad' => $detalle->cantidad,
                    'precio' => $detalle->precio_unitario,
                ];
            })
        ]);
    }

    public function exportPdf($id)
    {
        $order = Order::with('orderDetails')->findOrFail($id);
        $pdf = Pdf::loadView('filament.pages.orders.pdf', compact('order'));
        return $pdf->download("orden-{$order->id}.pdf");
    }


    public function generarFactura($id)
    {
        Log::info('generarFactura  ID:', ['order_id' => $id]);
        $order = Order::with('orderDetails')->findOrFail($id);
        $facturaExistente = Invoice::where('order_id', $order->id)->exists();
    

        if (!$facturaExistente) {
            $factura = Invoice::create([
            'client_id' => $order->client_id ?? null,
            'order_id' => $order->id,
            'monto_total' => $order->total,
            'tipo_factura' => 'A',
            'fecha_factura' => now(),
]);

            return response()->json(['success' => true,]);
        }

        return response()->json(['success' => false,]);
    }
} 


