<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentType extends Model
{
    use HasFactory; 
    
     protected $fillable = [
        'nombre',
    ];


    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
