<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
        protected $fillable = [
        'order_id',
        'nombre_producto',
        'cantidad',
        'precio_unitario',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
