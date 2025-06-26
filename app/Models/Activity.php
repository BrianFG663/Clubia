<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Activity extends Model
{
    protected $fillable = [
        'nombre',
        'institution_id',
        'descripcion',
    ];

    public function sub_activities(): HasMany
    {
        return $this->hasMany(Sub_activity::class);
    }

    public function institution(): HasOne
    {
        return $this->hasOne(Institution::class);
    }
}
