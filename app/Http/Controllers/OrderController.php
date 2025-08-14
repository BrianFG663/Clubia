<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    function obtenerDetalles($id)
    {
        $orden = Order::with('orderDetails', 'supplier')->findOrFail($id);

        return response()->json([
            'proveedor' => $orden->supplier->nombre, // suponiendo la relación supplier en Order
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

    public function eliminarDetalle($detalleId)
        {
            $detalle = OrderDetail::find($detalleId);
            if (!$detalle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Detalle no encontrado'
                ]);
            }

            $ordenId = $detalle->order_id;
            $detalle->delete();

            return response()->json([
                'success' => true,
                'message' => 'Detalle eliminado',
                'orden_id' => $ordenId
            ]);
        }





}
