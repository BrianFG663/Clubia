<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    protected $fillable = [
        'nombre',
    ];

      public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
}
