<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payment_type_id',
        'user_id',
        'monto',
        'fecha',
    ];
}
