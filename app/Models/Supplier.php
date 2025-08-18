<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nombre',
        'telefono',
        'direccion',
        'cuit',
        'condicion_id'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class,'condicion_id');
    }

}
