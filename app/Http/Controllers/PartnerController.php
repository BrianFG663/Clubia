<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Partner;
use App\Models\SubActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PartnerController extends Controller
{

    public function panelSocio()
    {

        $socioId = Auth::guard('partner')->id();

        $partner = Partner::with([
            'invoices',
            'memberTypes',
            'state',
            'subActivities',
            'responsable',
            'familyMembers.invoices',
            'responsable.familyMembers'
        ])->find($socioId);

        $facturasPagas = [];
        $facturasInpagas = [];


        foreach ($partner->invoices as $factura) {
            if ($factura->estado_pago == 1) {
                $facturasPagas[] = $factura;
            } else {
                $facturasInpagas[] = $factura;
            }
        }

        foreach ($partner->familyMembers as $familiar) {
            foreach ($familiar->invoices as $factura) {
                    if ($factura->estado_pago == 1) {
                        $facturasPagas[] = $factura;
                    } else {
                        $facturasInpagas[] = $factura;
                    }
                }
        }

        return view('partner.panel', compact('partner', 'facturasPagas', 'facturasInpagas'));
    }

    public function validacionLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('partner')->attempt($request->only('email', 'password'))) {
            return redirect('/panel/socio');
        }

        return back()->withErrors([
            'email' => 'Credenciales inválidas.',
            'password' => 'Credenciales inválidas.',
        ]);
    }

    public function facturasInpagas(Request $request)
    {

        $socio = Partner::find($request->socio);

        if ($socio->jefe_grupo == 1) {

            $partner = Partner::with([
                'invoices' => function ($query) {
                    $query->where('estado_pago', 0)
                        ->with(['memberType', 'subActivity']);
                },
                'familyMembers.invoices' => function ($query) {
                    $query->where('estado_pago', 0)
                        ->with(['memberType', 'subActivity']);
                }
            ])->find($request->socio);


            return response()->json(['mensaje' => true, 'jefe' => true, 'socio' => $partner]);
        } else {
            $partner = Partner::with([
                'invoices' => function ($query) {
                    $query->where('estado_pago', 0)
                        ->with(['memberType', 'subActivity']);
                }
            ])->find($request->socio);

            return response()->json(['mensaje' => false, 'jefe' => false, 'socio' => $partner]);
        }
    }

    public function facturasPagas(Request $request)
    {

        $socio = Partner::find($request->socio);

        if ($socio->jefe_grupo == 1) {

            $partner = Partner::with([
                'invoices' => function ($query) {
                    $query->where('estado_pago', 1)
                        ->with(['memberType', 'subActivity']);
                },
                'familyMembers.invoices' => function ($query) {
                    $query->where('estado_pago', 1)
                        ->with(['memberType', 'subActivity']);
                }
            ])->find($request->socio);


            return response()->json(['mensaje' => true, 'jefe' => true, 'socio' => $partner]);
        } else {
            $partner = Partner::with([
                'invoices' => function ($query) {
                    $query->where('estado_pago', 1)
                        ->with(['memberType', 'subActivity']);
                }
            ])->find($request->socio);

            return response()->json(['mensaje' => false, 'jefe' => false, 'socio' => $partner]);
        }
    }

    public function cambiarContrasena(Request $request){

        $partnerId = Auth::guard('partner')->id();
        $partner =Partner::find($partnerId);

        if($request->contrasena !== $request->contrasenaConfirmar){
            return back()->withErrors([
                'contrasena' => 'Las contraseñas deben coincidir.',
            ]);
        }

        if(Hash::check($request->contrasena, $partner->password)){
            return back()->withErrors([
                'contrasena' => 'La contrasena no puede ser la misma a la de antes.',
            ]);
        }

        if(strlen($request->contrasena) < 5){
            return back()->withErrors([
                'contrasena' => 'Debe tener al menos 6 caracteres.',
            ]);
        }

        if(!preg_match('/^[a-zA-Z0-9]+$/', $request->contrasena)){
            return back()->withErrors([
                'contrasena' => 'No se puede ingresar caracteres especiales.',
            ]);
        }
        
        $partner->password = Hash::make($request->contrasena);
        $partner->password_changed = true;
        $partner->save();

        return redirect()->route('partner.panel');
    }

    


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
        Log::info('Llega a eliminarIntegrante', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'body' => $request->all(),
            'id' => $request->id,
        ]);

        $integrante = Partner::find($request->id);

        if (!$integrante) {
            Log::warning('Integrante no encontrado', ['id' => $request->id]);
            return response()->json(['mensaje' => false, 'error' => 'Integrante no existe']);
        }

        $responsableId = $integrante->responsable_id;

        $tieneIntegrantes = Partner::where('responsable_id', $responsableId)
            ->where('id', '<>', $integrante->id)
            ->exists();

        Partner::where('id', $responsableId)->update([
            'jefe_grupo' => $tieneIntegrantes ? 1 : 0
        ]);

        $integrante->responsable_id = null;
        $integrante->save();


        return response()->json([
            'mensaje' => true,
            'responsable' => $responsableId
        ]);
    }


    public function buscarIntegrante(Request $request)
    {

        $integrante = Partner::with([
            'memberTypes',
            'subActivities.activity',
            'familyMembers',
            'responsable'
        ])->where('dni', $request->dni)->first();

        if ($integrante->jefe_grupo == 1) {

            return response()->json(['mensaje' => true, 'jefe' => true, 'integrante' => $integrante]);
        }

        if ($integrante) {
            if (!empty($integrante->responsable)) {
                $jefe =  Partner::with([
                    'familyMembers',
                ])->where('id', $integrante->responsable->id)->firstOrFail();
                $familiares =  $jefe->familyMembers;
                return response()->json(['mensaje' => true, 'responsable' => true, 'integrante' => $integrante, 'familiares' => $familiares]);
            } else {
                return response()->json(['mensaje' => true, 'responsable' => false, 'integrante' => $integrante, 'familiares' => []]);
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

        if (!$socioId) {
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
