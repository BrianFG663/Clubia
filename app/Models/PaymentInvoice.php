<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentInvoice extends Model
{
    Use HasFactory;
    
        protected $fillable = [
        'payment_id',
        'invoice_id',
        'monto_asignado',
    ];
}
