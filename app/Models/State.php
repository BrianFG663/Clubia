<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    Use HasFactory;
    
    protected $fillable = [
        'nombre',
    ];

    public function partners(): HasMany
    {
        return $this->hasMany(Partner::class);
    }
}
