<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Minor extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nombre',
        'apellido',
        'user_id',
        'dni',
        'fecha_nacimiento',
        'relacion',
    ];

    public function user():HasOne
    {
        return $this->HasOne(User::class);
    }
}
