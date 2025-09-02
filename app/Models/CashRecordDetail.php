<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashRecordDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'descripcion',
        'total',
        'fecha',
        'cash_record_id',
        'user_id',
        'tipo',
    ];

    public function cashRecord(): BelongsTo
    {
        return $this->belongsTo(CashRecord::class);
    }

        public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
