<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    public function buscarProductoVenta(Request $request){
        $query = $request->get('query');
        $productos = 
        Product::where('nombre', 'like', '%' . $query . '%')
        ->get();
        
        return response()->json($productos);
    }

    public function registrarVenta(Request $request){
        $productos = $request->all();
        Log::info('Datos de la venta: ', $request->all());

        
        $venta = Sale::create([
            'user_id' => Auth::user()['id'],
            'total' => $request->total
        ]);

        foreach ($productos['productos'] as $producto) {
            $subTotal = $producto['precio']*$producto['cantidad'];
            SaleDetail::create([
                'sale_id' => $venta->id,
                'product_id' => $producto['id'],
                'cantidad' => $producto['cantidad'],
                'subtotal' => $subTotal
            ]);
        } 

        if (empty($productos)) {
            return response()->json(['error' => 'false'], 400);
        }

        return response()->json(['message' => 'Venta recibida correctamente.', 'productos' => $productos]);
    }

    public function facturasVentas(Request $request){
        
        $facturas = Invoice::with('sale.user','sale.saleDetails')
        ->whereNotNull('sale_id')
        ->get();



        if ($facturas->isNotEmpty()) {
            return response()->json(['mensaje' => true, 'facturas' => $facturas]);
        } else {
            return response()->json(['mensaje' => false]);
        }
    }
}
