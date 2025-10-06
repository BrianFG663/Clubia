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
    dd('¡LLEGA AL CONTROLADOR!', $request->all());  // Muestra en respuesta si llega
    
    // Resto de tu código...
}


    public function buscarIntegrante(Request $request)
    {

        $integrante = Partner::with([
            'memberTypes',
            'subActivities.activity',
            'familyMembers',
            'responsable'
        ])->where('dni', $request->dni)->first();

        if($integrante->jefe_grupo == 1){

            return response()->json(['mensaje' =>true, 'jefe'=>true, 'integrante'=>$integrante]);
        }

        if ($integrante) {
            if (!empty($integrante->responsable)) {
                $jefe =  Partner::with([
                    'familyMembers',
                ])->where('id', $integrante->responsable->id)->firstOrFail();
                $familiares =  $jefe->familyMembers;
                return response()->json(['mensaje' => true, 'responsable'=>true, 'integrante' => $integrante, 'familiares' => $familiares]);
            } else {
                return response()->json(['mensaje' => true,'responsable'=>false ,'integrante' => $integrante, 'familiares' => []]);
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

    public function buscarGrupo(Request $request)
    {
        $query = trim($request->input('filtro'));

        $jefes = Partner::whereRaw("CONCAT(nombre, ' ', apellido) LIKE ?", ["%{$query}%"])
            ->orWhere('dni', 'like', "%{$query}%")
            ->limit(20)
            ->get();


        if ($jefes->isEmpty()) {
            return response()->json([
                'mensaje' => false,
                'message' => 'No se encontraron familiares.'
            ]);
        }

        $resultado = $jefes->map(function ($jefe) {
            return [
                'id' => $jefe->id,
                'nombre' => $jefe->nombre,
                'apellido' => $jefe->apellido,
                'dni' => $jefe->dni,
                'email' => $jefe->email,
                'telefono' => $jefe->telefono,
            ];
        });

        return response()->json([
            'mensaje' => true,
            'jefes' => $resultado
        ]);
    }
}
