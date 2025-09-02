<?php

namespace App\Http\Controllers;

use App\Models\CashRecord;
use App\Models\CashRecordDetail;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as FacadesLog;

class CashRecordDetailController extends Controller
{
    public function agregarMovimiento(Request $request)
    {

        $fecha = Carbon::now('America/Argentina/Buenos_Aires')->toDateString();
        $record = CashRecord::where('fecha', $fecha)
            ->where('institution_id', $request->institucion)
            ->first();

        FacadesLog::info('Contenido de $record:', ['record' => $request->institucion]);

        if ($record) {
            CashRecordDetail::create([
                'cash_record_id' => $record->id,
                'user_id' => Auth::id(),
                'descripcion' => $request->descripcion,
                'total' => $request->total,
                'fecha' => $fecha,
                'tipo' => $request->tipo
            ]);

            if ($request->tipo == 'salida') {
                $record->total -= $request->total; 
            }

            if($request->tipo == 'entrada'){
                $record->total += $request->total; 
            }

            $record->save();

            return response()->json(['mensaje' => true]);
        } else {
            return response()->json(['mensaje' => false]);
        }
    }

    public function eliminarMovimiento(Request $request){

        $movimiento = CashRecordDetail::with('cashRecord')->find($request->movimiento);
        $movimiento->delete();

        if ($movimiento->tipo == 'salida') {
                $movimiento->cashRecord->total += $movimiento->total;
            }

        
        if($movimiento->tipo == 'entrada'){
            $movimiento->cashRecord->total -= $movimiento->total;
        }
        
        $movimiento->cashRecord->save();
        FacadesLog::info('movimiento:', ['movimiento' => $movimiento]);

        return response()->json(['mensaje' => true]);
    }
}
