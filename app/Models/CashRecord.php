<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashRecord extends Model
{
   use HasFactory;

    protected $fillable = [
        'fecha',
        'institution_id',
        'total',

    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function cashRecordsDetails(): HasMany
    {
        return $this->hasMany(CashRecordDetail::class);
    }
    

}
