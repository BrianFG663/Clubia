<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Fee extends Model
{
    protected $fillable = [
        'sub_activity_id',
        'monto',
    ];

    public function sub_activity(): HasOne
    {
        return $this->hasOne(Sub_activity::class);
    }

}
