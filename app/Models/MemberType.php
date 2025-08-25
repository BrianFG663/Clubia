<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MemberType extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'nombre',
        'arancel',
    ];

    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
     
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

}
