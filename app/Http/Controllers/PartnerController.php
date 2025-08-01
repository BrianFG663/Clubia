<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PartnerController extends Controller
{
    public function detallesGrupoFamiliar(Request $request)
    {
        $jefe = Partner::with('familyMembers')->find($request->id);

        if (count($jefe->familyMembers) == 0) {
            return response()->json(['mensaje' => false]);
        } else {
            return response()->json(['mensaje' => true, 'jefe' => $jefe, 'familia' => $jefe->familyMembers]);
        }
    }

    public function eliminarIntegrante(Request $request)
    {

        $integrante = Partner::find($request->id);
        $responsable = $integrante->responsable_id;

        if ($integrante) {
            $integrante->responsable_id = NULL;
            $integrante->save();
            return response()->json(['mensaje' => true, 'responsable' => $responsable]);
        }else{
            return response()->json(['mensaje' => false]);
        }
    }

    public function buscarIntegrante(Request $request){

        $integrante = Partner::firstWhere('dni', $request->dni);
        Log::info('Datos del request:', $request->all());

        if($integrante){
            return response()->json(['mensaje' => true, 'integrante' => $integrante]);
        }else{
            return response()->json(['mensaje' => false]);
        }
    }

    public function agregarIntegranteGrupoFamiliar(Request $request){

        $integrante = Partner::firstWhere('dni', $request->dni);

        if ($integrante) {
            $integrante->responsable_id = $request->responsable_id;
            $integrante->save();
            return response()->json(['mensaje' => true]);
        }else{
            return response()->json(['mensaje' => false]);
        }
    }
}
