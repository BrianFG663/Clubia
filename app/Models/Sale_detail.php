<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale_detail extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'cantidad',
        'subtotal',
    ];
}
