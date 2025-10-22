<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\Invoice;
use App\Models\PaymentLink;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;

class PaymentLinkService
{
    public function generarLinkSocio(Partner $titular, string $mes): ?PaymentLink
    {
        try {
            Log::info("🔹 Generando link de pago para socio {$titular->id} - mes {$mes}");

            // Colección de IDs de socios a incluir
            $socios = collect([$titular->id]);

            if ($titular->jefe_grupo) {
                $dependientes = Partner::where('responsable_id', $titular->id)
                    ->pluck('id'); // solo IDs
                $socios = $socios->concat($dependientes);
            }

            Log::info("Socios a incluir en el link", ['count' => $socios->count(), 'ids' => $socios->all()]);

            // Traer facturas impagas de todos los socios en ese mes
            $facturas = Invoice::whereIn('client_id', $socios)
                ->where('estado_pago', false)
                ->where('fecha_factura', $mes)
                ->get();

            if ($facturas->isEmpty()) {
                Log::info("No hay facturas impagas para {$titular->id} en el mes {$mes}");
                return null;
            }

            Log::info("Facturas incluidas en el link", ['count' => $facturas->count()]);

            // Preparar items para Mercado Pago
            $items = $facturas->map(function ($f) {
                $tipo = $f->sub_activity_id
                    ? "Subactividad {$f->sub_activity_id}"
                    : "Tipo de socio {$f->member_type_id}";

                return [
                    'title' => "Factura {$f->id} - {$tipo}",
                    'quantity' => 1,
                    'unit_price' => (float) $f->monto_total,
                    'currency_id' => 'ARS',
                ];
            })->toArray();

            Log::info("Items para Mercado Pago", ['items' => $items]);

            // Configuración Mercado Pago
            $tokenAcceso = 'APP_USR-3817898036410630-101918-fca5c56641da373694aea20236fd8abb-2935832270';
            MercadoPagoConfig::setAccessToken($tokenAcceso);

            $cliente = new PreferenceClient();

            // Email de sandbox
            $emailPayer = 'test_user_1236279020@testuser.com';

            $preferencia = $cliente->create([
                'items' => $items,
                'auto_return' => 'approved',
                'payer' => ['email' => $emailPayer],
                'back_urls' => [
                    'success' => 'https://ee80ce8eaef6.ngrok-free.app',
                    'failure' => 'https://ee80ce8eaef6.ngrok-free.app',
                    'pending' => 'https://ee80ce8eaef6.ngrok-free.app',
                ],

            ]);

            Log::info('Preferencia raw', ['preferencia' => $preferencia]);
            Log::info("✅ Preferencia creada para socio {$titular->id}", [
                'checkout_url' => $preferencia->init_point,
            ]);

            // Guardar link en DB
            $paymentLink = PaymentLink::updateOrCreate(
                [
                    'partner_id' => $titular->id,
                    'periodo' => $mes,
                ],
                [
                    'link_mercado_pago' => $preferencia->init_point,
                    'preference_id' => $preferencia->id,
                    'monto_total' => $facturas->sum('monto_total'),
                    'estado' => 'pendiente',
                    'fecha_generacion' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            Log::info("💾 Link guardado en DB", [
                'payment_link_id' => $paymentLink->id,
            ]);

            return $paymentLink;

        } catch (MPApiException $e) {
            Log::error("❌ Error en API MercadoPago", ['mensaje' => $e->getMessage()]);
            return null;
        } catch (\Throwable $e) {
            Log::error("❌ Error inesperado al generar link de pago", ['mensaje' => $e->getMessage()]);
            return null;
        }
    }
}
