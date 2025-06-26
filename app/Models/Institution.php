<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'ciudad',
    ];

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function member_types(): HasMany
    {
        return $this->hasMany(Member_type::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }


}
