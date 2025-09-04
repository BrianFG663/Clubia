<?php

namespace App\Jobs;

use App\Models\Partner;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FacturarSocio implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $partnerId;
    protected $institutionId;
    protected $fechaFactura;
    protected $tipos;
    /**
     * Create a new job instance.
     */
    public function __construct($partnerId, $institutionId, $tipos, $fechaFactura)
    {
        $this->partnerId = $partnerId;
        $this->institutionId = $institutionId;
        $this->tipos = $tipos;
        $this->fechaFactura = $fechaFactura;
    }

    /**
     * Execute the job.
     */
    public function handle(): void{

    $partner = Partner::find($this->partnerId);

    if (!$partner || $partner->state_id !== 1) {
        Log::info("Socio {$this->partnerId} no facturado por status_id: {$partner?->state_id}");
        return;
    }
    
    // Facturar por cada tipoSocio

    foreach ($this->tipos['tipoSocio'] ?? [] as $tipoSocio) {
        Invoice::create([
            'client_id' => $this->partnerId,
            'institution_id' => $this->institutionId,
            'member_type_id' => $tipoSocio->id,   
            'sub_activity_id' => null,
            'sale_id' => null,
            'order_id' => null,
            'monto_total' => $tipoSocio->arancel,   
            'fecha_factura' => $this->fechaFactura,
            'tipo_factura' => 'A', 
        ]);
        Log::info("Factura creada para socio: {$this->partnerId} con member_type_id: {$tipoSocio->id}");
    }

    // Facturar por cada subActividad
    foreach ($this->tipos['subActividad'] ?? [] as $subActividad) {
        Invoice::create([
            'client_id' => $this->partnerId,
            'institution_id' => $this->institutionId,
            'member_type_id' => null,
            'sub_activity_id' => $subActividad->id, 
            'sale_id' => null,
            'order_id' => null,
            'monto_total' => $subActividad->monto,  
            'fecha_factura' => $this->fechaFactura,
            'tipo_factura' => 'A', 
        ]);
        Log::info("Factura creada para socio: {$this->partnerId} con sub_activity_id: {$subActividad->id}");
    }
}

}
