<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLink extends Model
{
     use HasFactory;

    protected $fillable = [
        'partner_id',
        'link_mercado_pago',
        'preference_id',
        'monto_total',
        'estado',
        'periodo',
        'fecha_generacion',
        'fecha_pago',
    ];

    
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
