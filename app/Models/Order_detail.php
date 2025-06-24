<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{
    protected $fillable = [
        'order_id',
        'nombre_producto',
        'cantidad',
        'precio_unitario',
    ];
}
