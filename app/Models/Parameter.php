<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Parameter extends Model{
    
    protected $fillable = [
        'umbral_facturas_impagas',
        'institution_id'
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

}
