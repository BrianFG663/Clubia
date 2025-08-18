<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function proveedores()
    {
        $proveedores = Supplier::with([
            'orders'
        ])->get();

        return response()->json(['mensaje' => true, 'proveedores' => $proveedores]);
    }

    public function facturasProveedores(Request $request){

        $proveedor = $request->proveedor;

        $facturas = Invoice::with(['order.supplier'])
            ->whereHas('order.supplier', function ($query) use ($proveedor) {
                $query->where('id', $proveedor);
            })->get();


        if($facturas->isNotEmpty()){
            return response()->json(['mensaje' => true, 'facturas' => $facturas]);
        }else{
            return response()->json(['mensaje' => false]);
        }
    }

}
