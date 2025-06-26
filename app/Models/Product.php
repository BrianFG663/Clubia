<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'nombre',
        'category_id',
        'descripcion',
        'precio',
        'stock',
    ];

    public function category(): HasOne
    {
        return $this->hasOne(Category::class);
    }
}
