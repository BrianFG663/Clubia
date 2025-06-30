<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentInvoice extends Model
{
        protected $fillable = [
        'payment_id',
        'invoice_id',
        'monto_asignado',
    ];
}
