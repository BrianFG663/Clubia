<?php

namespace App\Http\Controllers;

use App\Models\CashRecord;
use Illuminate\Support\Facades\Log;
use App\Models\Partner;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CashRecordController extends Controller
{
    public function cashRecords(Request $request)
    {

        $institutionId = $request->institucion;

        if($request->fecha == false){
            $fecha = Carbon::now('America/Argentina/Buenos_Aires')->toDateString();
        }else{
            $fecha = $request->fecha;
        }


        $record = CashRecord::with('cashRecordsDetails.responsable')
        ->where('institution_id', $institutionId)
        ->orderBy('fecha', 'desc')
        ->whereDate('fecha', $fecha)
        ->get();

        if ($record->isNotEmpty()) {
            return response()->json(['mensaje'=>true,'records' => $record]);
        } else {
            $record = CashRecord::create([
                'fecha' => $fecha,
                'institution_id' => $institutionId,
                'total' => 0,
            ]);

            return response()->json(['mensaje'=>true,'records' => $record]);
        }
    }

    public function cashRecord(Request $request){

        $record = CashRecord::with('cashRecordsDetails.responsable','institution')
        ->where('id',$request->record) 
        ->first();

        if($record){
            return response()->json(['mensaje'=>true,'records' => $record]);
        }else{
            return response()->json(['mensaje'=>false]);
        }
    }

    public function eliminarCashRecord(Request $request){

        $record = CashRecord::find($request->record);

        if($record){
            $record->delete();
            return response()->json(['mensaje'=>true,'records' => $record]);
        }else{
            return response()->json(['mensaje'=>false]);
        }
    }

    public function eliminarCashRecordVacio()
    {
        $records = CashRecord::doesntHave('cashRecordsDetails')->get();

        if ($records->count() > 0) {
            foreach ($records as $record) {
                $record->delete();
            }

            return response()->json(['mensaje' => true,'eliminados' => $records->count()]);
        }

        return response()->json(['mensaje' => false,'eliminados' => 0]);
    }
}
