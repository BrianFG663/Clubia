<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sub_activity extends Model
{
    protected $fillable = [
        'activity_id',
        'nombre',
        'descripcion',
    ];
}
