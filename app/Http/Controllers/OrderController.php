<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

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
        return $pdf->stream("orden-{$order->id}.pdf");
    }

}
