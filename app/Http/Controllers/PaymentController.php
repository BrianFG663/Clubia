<?php

namespace App\Http\Controllers;

use App\Models\CashRecord;
use App\Models\CashRecordDetail;
use App\Models\Invoice;
use App\Models\Parameter;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\PaymentInvoice;
use App\Models\PaymentLink;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;

class PaymentController extends Controller
{
    /**
     * Pago presencial
     */
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

            // Crear pago
            $montoTotal = $facturas->sum('monto_total');
            $payment = Payment::create([
                'partner_id' => $partnerId,
                'payment_type_id' => $paymentTypeId,
                'monto' => $montoTotal,
                'fecha_pago' => now(),
            ]);

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

            // Actualizar caja diaria por institución
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

            $this->actualizarEstadosYSubactividades($partnerId);

            // Actualizar PaymentLink si existe (solo marcar como pagado)
            $periodo = $facturas->first()->fecha_factura ?? null;
            if ($periodo) {
                $paymentLink = PaymentLink::where('partner_id', $partnerId)
                    ->where('periodo', $periodo)
                    ->first();
                if ($paymentLink) {
                    $paymentLink->estado = 'pagado';
                    $paymentLink->fecha_pago = now();
                    $paymentLink->save();
                    Log::info("✅ Link de pago presencial marcado como pagado para socio {$partnerId} - periodo {$periodo}");
                }
            }

            DB::commit();
            return response()->json(['mensaje' => true]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("❌ Error al procesar pago presencial: " . $e->getMessage());
            return response()->json(['mensaje' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Webhook de Mercado Pago (pagos online)
     */
    public function webhookMP(Request $request)
{
    $data = $request->all();
    Log::info('🔔 Webhook Mercado Pago recibido', $data);

    $paymentId = $data['data']['id'] ?? null;
    if (!$paymentId) {
        return response()->json(['mensaje' => 'No se recibió payment_id'], 400);
    }

    // Consultar pago en MP
    $tokenAcceso = 'APP_USR-3817898036410630-101918-fca5c56641da373694aea20236fd8abb-2935832270';
    MercadoPagoConfig::setAccessToken($tokenAcceso);
    $client = new \MercadoPago\Client\Payment\PaymentClient();
    $payment = $client->get($paymentId);

    $preferenceId = $payment->preference_id ?? null;
    if (!$preferenceId) {
        Log::warning("⚠️ Pago recibido sin preference_id");
        return response()->json(['mensaje' => 'Sin preference_id'], 400);
    }

    $paymentLink = PaymentLink::where('preference_id', $preferenceId)->first();
    if (!$paymentLink) {
        return response()->json(['mensaje' => 'Link no encontrado'], 404);
    }

    // Marcar como pagado si está aprobado
    if ($payment->status === 'approved') {
        $paymentLink->update([
            'estado' => 'pagado',
            'fecha_pago' => now(),
        ]);

        Invoice::where('client_id', $paymentLink->partner_id)
            ->where('fecha_factura', $paymentLink->periodo)
            ->update(['estado_pago' => true]);

        $this->actualizarEstadosYSubactividades($paymentLink->partner_id);
    }

    Log::info("✅ Webhook procesado correctamente para socio {$paymentLink->partner_id}");
    return response()->json(['mensaje' => 'OK']);
}







    /**
     * Función compartida para actualizar estados y subactividades
     */
    private function actualizarEstadosYSubactividades($partnerId)
    {
        $titular = Partner::findOrFail($partnerId);
        $sociosAEvaluar = collect([$titular]);
        if ($titular->jefe_grupo) {
            $dependientes = Partner::where('responsable_id', $titular->id)->get();
            $sociosAEvaluar = $sociosAEvaluar->concat($dependientes);
        }

        $parameters = Parameter::all()->keyBy('institution_id');

        foreach ($sociosAEvaluar as $socio) {
            foreach ($parameters as $institutionId => $param) {
                // Tipo de socio
                $cantTipoSocio = Invoice::where('client_id', $socio->id)
                    ->where('estado_pago', false)
                    ->whereNotNull('member_type_id')
                    ->where('institution_id', $institutionId)
                    ->count();

                $nombreEstado = $cantTipoSocio > $param->umbral_facturas__cuotas_impagas ? 'Inactivo' : 'Activo';
                $estado = State::where('nombre', $nombreEstado)->first();

                if ($estado && $socio->state_id !== $estado->id) {
                    $socio->update(['state_id' => $estado->id]);
                }

                // Subactividades
                $todasSubIds = Invoice::where('client_id', $socio->id)
                    ->whereNotNull('sub_activity_id')
                    ->distinct()
                    ->pluck('sub_activity_id')
                    ->toArray();

                $subIdsActuales = $socio->subActivities()->pluck('sub_activities.id')->toArray();

                foreach ($todasSubIds as $subId) {
                    $cantImpagasSub = Invoice::where('client_id', $socio->id)
                        ->where('estado_pago', false)
                        ->where('sub_activity_id', $subId)
                        ->count();

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
    }
}
