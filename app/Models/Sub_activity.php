<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sub_activity extends Model
{
    protected $fillable = [
        'activity_id',
        'nombre',
        'descripcion',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function fee(): HasMany
    {
        return $this->hasMany(Fee::class);
    }
}
