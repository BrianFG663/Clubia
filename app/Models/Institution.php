<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    use HasFactory;

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

    public function memberTypes()
    {
        return $this->hasMany(MemberType::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function cashRecords(): HasMany
    {
        return $this->hasMany(CashRecord::class);
    }
}
