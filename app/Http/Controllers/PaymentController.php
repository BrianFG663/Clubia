<?php

namespace App\Http\Controllers;

use App\Models\CashRecord;
use App\Models\CashRecordDetail;
use App\Models\Invoice;
use App\Models\Parameter;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\PaymentInvoice;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function pagarFacturas(Request $request)
    {
        $ids = $request->input('facturas');
        $partnerId = $request->input('partner_id');
        $paymentTypeId = $request->input('payment_type_id');

        DB::beginTransaction();

        try {
            $facturas = Invoice::with(['subActivity', 'memberType', 'institution'])
                ->whereIn('id', $ids)
                ->get();

            //crear pago
            $montoTotal = $facturas->sum('monto_total');
            $payment = Payment::create([
                'partner_id' => $partnerId,
                'payment_type_id' => $paymentTypeId,
                'monto' => $montoTotal,
                'fecha_pago' => now(),
            ]);

            //crear datos en la tabla pivot
            $institucionesProcesadas = [];
            foreach ($facturas as $factura) {
                PaymentInvoice::create([
                    'payment_id' => $payment->id,
                    'invoice_id' => $factura->id,
                    'monto_asignado' => $factura->monto_total,
                ]);

                $factura->update(['estado_pago' => true]);
                $institucionesProcesadas[] = $factura->institution_id;
            }

            $institucionesProcesadas = array_values(array_unique($institucionesProcesadas));

            //Actualizar o crear caja diaria
            foreach ($institucionesProcesadas as $institutionId) {
                $facturasPorInst = $facturas->filter(fn($f) => $f->institution_id == $institutionId);
                $totalPorInst = $facturasPorInst->sum('monto_total');

                $cash = CashRecord::firstOrCreate(
                    ['institution_id' => $institutionId, 'fecha' => now()->toDateString()],
                    ['total' => 0]
                );
                $cash->total += $totalPorInst;
                $cash->save();

                foreach ($facturasPorInst as $factura) {
                    $tipoFactura = $factura->sub_activity_id ? 'Subactividad' : 'Tipo de socio';
                    CashRecordDetail::create([
                        'cash_record_id' => $cash->id,
                        'descripcion' => "Cobro factura ({$tipoFactura})",
                        'tipo' => 'Entrada',
                        'total' => $factura->monto_total,
                        'fecha' => now()->toDateString(),
                        'user_id' => Auth::id() ?? 1,
                    ]);
                }
            }

            $titular = Partner::findOrFail($partnerId);
            $sociosAEvaluar = collect([$titular]);
            if ($titular->jefe_grupo) {
                $dependientes = Partner::where('responsable_id', $titular->id)->get();
                $sociosAEvaluar = $sociosAEvaluar->concat($dependientes);
            }

            //comparar con parametros 
            $parameters = Parameter::all()->keyBy('institution_id');
            foreach ($sociosAEvaluar as $socio) {
                foreach ($parameters as $institutionId => $param) {
                    //tipo de socio
                    $cantTipoSocio = Invoice::where('client_id', $socio->id)
                        ->where('estado_pago', false)
                        ->whereNotNull('member_type_id')
                        ->where('institution_id', $institutionId)
                        ->count();

                if ($cantTipoSocio > $param->umbral_facturas__cuotas_impagas) {
                        $nombreEstado = 'Inactivo';
                    } else {
                        $nombreEstado = 'Activo';
                    }

                $estado = State::where('nombre', $nombreEstado)->first();

                if ($estado && $socio->state_id !== $estado->id) {
                    $socio->update(['state_id' => $estado->id]);
                }


                    //Todas las subactividades del socio (aunque ya no esté suscrito)
                        $todasSubIds = Invoice::where('client_id', $socio->id)
                            ->whereNotNull('sub_activity_id')
                            ->distinct()
                            ->pluck('sub_activity_id')
                            ->toArray();

                        //Subactividades activas
                        $subIdsActuales = $socio->subActivities()->pluck('sub_activities.id')->toArray();

                        foreach ($todasSubIds as $subId) {
                            $cantImpagasSub = Invoice::where('client_id', $socio->id)
                                ->where('estado_pago', false)
                                ->where('sub_activity_id', $subId)
                                ->count();
                            //baja de subactividad si supera los parametros ,
                            if ($cantImpagasSub > $param->umbral_facturas_subactividades_impagas) {
                                if (in_array($subId, $subIdsActuales)) {
                                    $socio->subActivities()->detach($subId);
                                }
                            } else {
                                if (!in_array($subId, $subIdsActuales)) {
                                    $socio->subActivities()->attach($subId);
                                   
                                }
                            }
                        }
                }
            }

            DB::commit();
            return response()->json(['mensaje' => true]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['mensaje' => false]);
        }
    }
}
