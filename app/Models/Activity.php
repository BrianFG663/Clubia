<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Activity extends Model
{
    Use HasFactory; 
    
    protected $fillable = [
        'nombre',
        'institution_id',
        'descripcion',
    ];

    public function subActivities(): HasMany
    {
        return $this->hasMany(SubActivity::class);
    }

    public function institution(): HasOne
    {
        return $this->hasOne(Institution::class);
    }

   
}
