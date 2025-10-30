<?php

namespace App\Jobs;

use App\Models\Partner;
use App\Services\PaymentLinkService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerarLinkSocio implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $partnerId;
    protected $periodo;

    public function __construct(int $partnerId, string $periodo)
    {
        $this->partnerId = $partnerId;
        $this->periodo = $periodo;
    }

    public function handle(): void
    {
        $partner = Partner::find($this->partnerId);

        if (!$partner) {
            Log::info("Socio {$this->partnerId} no encontrado para generar link");
            return;
        }

        Log::info("🔹 Generando link de pago para socio {$this->partnerId} - periodo {$this->periodo}");

        $service = new PaymentLinkService();
        $paymentLink = $service->generarLinkSocio($partner, $this->periodo);

        if ($paymentLink) {
            Log::info("✅ Link de pago generado y guardado en DB para socio {$this->partnerId}", [
                'link' => $paymentLink->link_mercado_pago,
            ]);
        } else {
            Log::error("❌ No se pudo generar link de pago para socio {$this->partnerId}");
        }
    }
}
