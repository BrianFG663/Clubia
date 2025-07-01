<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubActivity extends Model
{
    Use HasFactory; 
    
        protected $fillable = [
        'activity_id',
        'nombre',
        'descripcion',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function fee(): HasMany
    {
        return $this->hasMany(Fee::class);
    }
}
