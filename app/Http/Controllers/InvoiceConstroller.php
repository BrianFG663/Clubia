<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\FacturarSocio;
use App\Models\Invoice;
use App\Models\MemberType;
use App\Models\Partner;
use App\Models\SubActivity;
use Illuminate\Support\Facades\Log;
use Symfony\Component\VarDumper\VarDumper;

class InvoiceConstroller extends Controller
{
//FUNCIONES PARA PANEL DONDE SE GENERAN LAS FACTURAS 
    public function facturacionMasivaMensualSocio(Request $request)
    {
        $institutionId = $request->input('institution_id');
        $fechaFactura = $request->input('fecha_factura');
        Log::info("Fecha a facturar: {$fechaFactura}");

        $facturas = false;

        Partner::chunk(100, function ($socios) use ($institutionId, $fechaFactura, &$facturas) {
            foreach ($socios as $socio) {
                $tipos = $this->tipoSocioSubActividades($socio->id, $institutionId);

                $tiposAFacturar = ['tipoSocio' => [], 'subActividad' => []];

                // Filtrar tiposSocio sin factura para la fecha
                foreach ($tipos['tipoSocio'] as $tipoSocio) {
                    $existeFactura = Invoice::where('client_id', $socio->id)
                        ->where('fecha_factura', $fechaFactura)
                        ->where('member_type_id', $tipoSocio->id)
                        ->exists();

                    if (!$existeFactura) {
                        $tiposAFacturar['tipoSocio'][] = $tipoSocio;
                    }
                }

                // Filtrar subActividades sin factura para la fecha
                foreach ($tipos['subActividad'] as $subActividad) {
                    $existeFactura = Invoice::where('client_id', $socio->id)
                        ->where('fecha_factura', $fechaFactura)
                        ->where('sub_activity_id', $subActividad->id)
                        ->exists();

                    if (!$existeFactura) {
                        $tiposAFacturar['subActividad'][] = $subActividad;
                    }
                }

                // Solo despachar job si hay tipos/subActividades pendientes
                if (!empty($tiposAFacturar['tipoSocio']) || !empty($tiposAFacturar['subActividad'])) {
                    $facturas = true;
                    FacturarSocio::dispatch($socio->id, $institutionId, $tiposAFacturar, $fechaFactura);
                }
            }
        });

        return response()->json(['mensaje' => $facturas]);
    }

    public function facturarSocioIndividual(Request $request)
    {
        $facturasExistentes = [
            'sub_actividades' => [],
            'tipos_socio' => []
        ];

        $facturasCreadas = false;

        // Facturación de subactividades
        if (!empty($request->subActividades)) {
            foreach ($request->subActividades as $subActividadId) {
                $factura = Invoice::where('sub_activity_id', $subActividadId)
                    ->where('client_id', $request->socio)
                    ->where('fecha_factura', $request->fecha)
                    ->first();

                if (!$factura) {
                    $subActividad = SubActivity::with(['activity.institution'])
                        ->findOrFail($subActividadId);

                    Invoice::create([
                        'client_id' => $request->socio,
                        'institution_id' => $subActividad->activity->institution->id,
                        'member_type_id' => null,
                        'sub_activity_id' => $subActividad->id,
                        'sale_id' => null,
                        'order_id' => null,
                        'monto_total' => $subActividad->monto,
                        'fecha_factura' => $request->fecha,
                        'tipo_factura' => 'A',
                    ]);

                    $facturasCreadas = true;
                } else {
                    $subActividad = SubActivity::find($subActividadId);
                    $facturasExistentes['sub_actividades'][] = $subActividad->nombre;
                }
            }
        }

        // Facturación de tipos de socio
        if (!empty($request->tiposDeSocio)) {
            foreach ($request->tiposDeSocio as $tipoSocioId) {
                $factura = Invoice::where('member_type_id', $tipoSocioId)
                    ->where('client_id', $request->socio)
                    ->where('fecha_factura', $request->fecha)
                    ->first();

                if (!$factura) {
                    $tipoSocio = MemberType::with(['institution'])
                        ->findOrFail($tipoSocioId);

                    Invoice::create([
                        'client_id' => $request->socio,
                        'institution_id' => $tipoSocio->institution->id,
                        'member_type_id' => $tipoSocio->id,
                        'sub_activity_id' => null,
                        'sale_id' => null,
                        'order_id' => null,
                        'monto_total' => $tipoSocio->arancel,
                        'fecha_factura' => $request->fecha,
                        'tipo_factura' => 'A',
                    ]);

                    $facturasCreadas = true;
                } else {
                    $tipoSocio = MemberType::find($tipoSocioId);
                    $facturasExistentes['tipos_socio'][] = $tipoSocio->nombre;
                }
            }
        }

        return response()->json(['mensaje' => $facturasCreadas, 'facturas_existentes' => $facturasExistentes]);
    }




    public function tipoSocioSubActividades($socioId, $institutionId)
    {
        $socio = Partner::with([
            'memberTypes',
            'subActivities.activity'
        ])->findOrFail($socioId);

        $tipos = ['tipoSocio' => [], 'subActividad' => []];

        if ($socio->memberTypes->isNotEmpty()) {
            foreach ($socio->memberTypes as $tipoSocio) {
                if ($tipoSocio->institution_id == $institutionId) {
                    $tipos['tipoSocio'][] = $tipoSocio;
                }
            }
        }

        if ($socio->subActivities->isNotEmpty()) {
            foreach ($socio->subActivities as $subActividad) {
                if ($subActividad->activity && $subActividad->activity->institution_id == $institutionId) {
                    $tipos['subActividad'][] = $subActividad;
                }
            }
        }

        return $tipos;
    }


    public function notaCreditoFactura(Request $request){
        
        $idFactura = $request->id;

        $factura = Invoice::find($idFactura);

        if($factura){

            $factura->delete();
            return response()->json(['mensaje' => true]);

        }else{
            return response()->json(['mensaje' => false]);
        }


    }

    //FUNCIONES PARA PANEL DONDE SE PAGAN LAS FACTURAS 

    public function facturasImpagas(Partner $partner)
    {
        // Facturas del titular
        $facturasTitular = $partner->invoices()
            ->where('estado_pago', false)
            ->get()
            ->map(fn($factura) => [
                'id' => $factura->id,
                'tipo_factura' => $factura->tipo_factura,
                'subActivity' => $factura->subActivity?->nombre ?? '-',
                'memberType' => $factura->memberType?->nombre ?? '-',
                'institution' => $factura->institution?->nombre ?? '-',
                'fecha_factura' => $factura->fecha_factura,
                'monto_total' => $factura->monto_total,
                'partner_name' => $partner->nombre . ' ' . $partner->apellido,
            ]);

        // Facturas de familiares (si es jefe de grupo)
        $facturasFamiliares = collect();

        if ($partner->jefe_grupo) {
            $dependientes = Partner::where('responsable_id', $partner->id)->get();

            foreach ($dependientes as $dep) {
                $depFacturas = $dep->invoices()
                    ->where('estado_pago', false)
                    ->get()
                    ->map(fn($factura) => [
                        'id' => $factura->id,
                        'tipo_factura' => $factura->tipo_factura,
                        'subActivity' => $factura->subActivity?->nombre ?? '-',
                        'memberType' => $factura->memberType?->nombre ?? '-',
                        'institution' => $factura->institution?->nombre ?? '-',
                        'fecha_factura' => $factura->fecha_factura,
                        'monto_total' => $factura->monto_total,
                        'partner_name' => $dep->nombre . ' ' . $dep->apellido,
                    ]);

                $facturasFamiliares = $facturasFamiliares->concat($depFacturas);
            }
        }

        return response()->json([
            'partner' => $partner->nombre . ' ' . $partner->apellido,
            'tipo' => $partner->jefe_grupo,
            'facturasTitular' => $facturasTitular,
            'facturasFamiliares' => $facturasFamiliares,
        ]);

    }

    public function pagarFacturas(Request $request)
    {
        $ids = $request->input('facturas');

        if (!$ids || !is_array($ids)) {
            return response()->json(['mensaje' => false]); 
        }

        $actualizadas = Invoice::whereIn('id', $ids)
            ->update(['estado_pago' => true]);

        if ($actualizadas > 0) {
            return response()->json(['mensaje' => true]);
        }

        return response()->json(['mensaje' => false], 404); // Ninguna factura actualizada
    }



 public function facturasPagas(Partner $partner)
    {
        // Facturas del titular
        $facturasTitular = $partner->invoices()
            ->where('estado_pago', true)
            ->get()
            ->map(fn($factura) => [
                'id' => $factura->id,
                'tipo_factura' => $factura->tipo_factura,
                'subActivity' => $factura->subActivity?->nombre ?? '-',
                'memberType' => $factura->memberType?->nombre ?? '-',
                'institution' => $factura->institution?->nombre ?? '-',
                'fecha_factura' => $factura->fecha_factura,
                'monto_total' => $factura->monto_total,
                'partner_name' => $partner->nombre . ' ' . $partner->apellido,
            ]);

        // Facturas familiares si es jefe de grupo
        $facturasFamiliares = collect();

        if ($partner->jefe_grupo) {
            $dependientes = Partner::where('responsable_id', $partner->id)->get();

            foreach ($dependientes as $dep) {
                $depFacturas = $dep->invoices()
                    ->where('estado_pago', true)
                    ->get()
                    ->map(fn($factura) => [
                        'id' => $factura->id,
                        'tipo_factura' => $factura->tipo_factura,
                        'subActivity' => $factura->subActivity?->nombre ?? '-',
                        'memberType' => $factura->memberType?->nombre ?? '-',
                        'institution' => $factura->institution?->nombre ?? '-',
                        'fecha_factura' => $factura->fecha_factura,
                        'monto_total' => $factura->monto_total,
                        'partner_name' => $dep->nombre . ' ' . $dep->apellido,
                    ]);

                $facturasFamiliares = $facturasFamiliares->concat($depFacturas);
            }
        }

        return response()->json([
            'partner' => $partner->nombre . ' ' . $partner->apellido,
            'facturasTitular' => $facturasTitular,
            'facturasFamiliares' => $facturasFamiliares,
        ]);
    }


}
