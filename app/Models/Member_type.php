<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Member_type extends Model
{
    protected $fillable = [
        'institution_id',
        'nombre',
        'arancel',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function institution(): HasOne
    {
        return $this->hasOne(Institution::class);
    }
}
