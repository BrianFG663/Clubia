<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    protected $fillable = [
        'cliente_id',
        'user_id',
        'institution_id',
        'tipo_factura',
        'sale_id',
        'monto_total',
        'fecha_factura',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function institution(): HasOne
    {
        return $this->hasOne(Institution::class);
    }
}
