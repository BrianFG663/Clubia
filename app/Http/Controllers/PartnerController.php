<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Partner;
use App\Models\SubActivity;
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
        } else {
            return response()->json(['mensaje' => false]);
        }
    }

    public function buscarIntegrante(Request $request)
    {

        $integrante = Partner::with([
            'memberTypes',
            'subActivities.activity',
            'familyMembers',
            'responsable'
        ])->where('dni', $request->dni)->first();


        if ($integrante) {
            if (!empty($integrante->responsable)) {
                $jefe =  Partner::with([
                    'familyMembers',
                ])->where('id', $integrante->responsable->id)->firstOrFail();
                $familiares =  $jefe->familyMembers;

                return response()->json(['mensaje' => true, 'integrante' => $integrante, 'familiares' => $familiares]);
            } else {
                return response()->json(['mensaje' => true, 'integrante' => $integrante, 'familiares' => []]);
            }
        } else {
            return response()->json(['mensaje' => false]);
        }
    }

    public function agregarIntegranteGrupoFamiliar(Request $request)
    {

        $integrante = Partner::firstWhere('dni', $request->dni);

        if ($integrante) {
            $integrante->responsable_id = $request->responsable_id;
            $integrante->save();
            return response()->json(['mensaje' => true]);
        } else {
            return response()->json(['mensaje' => false]);
        }
    }

    public function validarInscripcionSubActividad(Request $request)
    {
        Log::info('Datos del request:', $request->all());

        $integrante = Partner::firstWhere('dni', $request->dni);
        $subActividad = SubActivity::find($request->subActividad);


        if ($integrante) {
            return response()->json(['mensaje' => true, 'integrante' => $integrante, 'subActividad' => $subActividad]);
        } else {
            return response()->json(['mensaje' => false]);
        }
    }

    public function inscribirSocioSubActividad(Request $request)
    {

        $integrante = Partner::firstWhere('dni', $request->dni);
        $integrante->subActivities()->syncWithoutDetaching($request->subActividad);

        return redirect()->back();
    }


    public function facturasSocios(Request $request)
    {

        $dni = $request->dni;
        $socioId = Partner::where('dni', $dni)->value('id');

        if(!$socioId){
            return response()->json(['socio' => false]);  
        }

        $facturas = Invoice::with('partner')
            ->where('client_id', $socioId)
            ->get();



        if ($facturas->isNotEmpty()) {
            return response()->json(['mensaje' => true, 'facturas' => $facturas]);
        } else {
            return response()->json(['mensaje' => false]);
        }
    }
}
