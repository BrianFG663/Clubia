<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Minor extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'fecha_nacimiento',
        'relacion',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
