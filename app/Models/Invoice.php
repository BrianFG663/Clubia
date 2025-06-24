<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'cliente_id',
        'user_id',
        'institution_id',
        'tipo_factura',
        'monto_total',
        'fecha',
    ];
}
