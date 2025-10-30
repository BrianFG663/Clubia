<?php

namespace App\Jobs;

use App\Models\Partner;
use App\Models\Invoice;
use App\Models\Parameter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Jobs\GenerarLinkSocio;


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
    public function handle(): void
    {
        $partner = Partner::with('invoices', 'memberTypes')->find($this->partnerId);

        if (!$partner) {
            Log::info("Socio {$this->partnerId} no encontrado");
            return;
        }

        // Si ya está inactivo no se factura
        if ($partner->state_id !== 1) {
            Log::info("Socio {$this->partnerId} no facturado porque está inactivo (state_id={$partner->state_id})");
            return;
        }

        // Traer parámetro según la institución del socio
        $parametro = Parameter::where('institution_id', $this->institutionId)->first();



        // Facturar por cada tipoSocio
        foreach ($this->tipos['tipoSocio'] ?? [] as $tipoSocio) {

            Invoice::create([
                'client_id'      => $this->partnerId,
                'institution_id' => $this->institutionId,
                'member_type_id' => $tipoSocio->id,
                'sub_activity_id' => null,
                'sale_id'        => null,
                'order_id'       => null,
                'monto_total'    => $tipoSocio->arancel,
                'fecha_factura'  => $this->fechaFactura,
                'tipo_factura'   => 'A',
            ]);

            Log::info("Factura creada para socio: {$this->partnerId} con member_type_id: {$tipoSocio->id}");

            if (!is_null($parametro->umbral_facturas_cuotas_impagas)) {
                $FechasCuotas = $partner->invoices()
                    ->where('estado_pago', 0)
                    ->whereNotNull('member_type_id')
                    ->pluck('fecha_factura')
                    ->unique()
                    ->count();

                if ($FechasCuotas > $parametro->umbral_facturas_cuotas_impagas) {
                    $partner->state_id = 2;
                    $partner->save();
                    Log::info("Socio {$this->partnerId} pasó a inactivo por superar umbral de facturas impagas");
                }
            }
        }

        // Facturar por cada subActividad
        foreach ($this->tipos['subActividad'] ?? [] as $subActividad) {

            Invoice::create([
                'client_id'      => $this->partnerId,
                'institution_id' => $this->institutionId,
                'member_type_id' => null,
                'sub_activity_id' => $subActividad->id,
                'sale_id'        => null,
                'order_id'       => null,
                'monto_total'    => $subActividad->monto,
                'fecha_factura'  => $this->fechaFactura,
                'tipo_factura'   => 'A',
            ]);

            Log::info("Factura creada para socio: {$this->partnerId} con sub_activity_id: {$subActividad->id}");


            if (!is_null($parametro->umbral_facturas_subactividades_impagas)) {
                $FechasSubActividades = $partner->invoices()
                    ->where('estado_pago', 0)
                    ->whereNotNull('sub_activity_id')
                    ->pluck('fecha_factura')
                    ->unique()
                    ->count();

                if ($FechasSubActividades > $parametro->umbral_facturas_subactividades_impagas) {
                    $partner->subActivities()->detach($subActividad->id);
                    Log::info("Socio {$this->partnerId} se eliminó de la subactividad {$subActividad->id}");
                }
            }
             
        }
        $periodo = $this->fechaFactura; // ya es "10-2025"
        GenerarLinkSocio::dispatch($this->partnerId, $periodo);
       

Log::info("✅ Job de generación de link de pago despachado para socio {$this->partnerId}");
    }
    
}
