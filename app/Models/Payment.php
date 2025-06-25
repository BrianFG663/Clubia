<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'payment_type_id',
        'user_id',
        'monto',
        'fecha_pago',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
