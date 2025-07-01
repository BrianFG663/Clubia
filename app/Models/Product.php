<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

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

    public function saleDetails(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }
}
