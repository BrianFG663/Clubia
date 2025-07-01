<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Fee extends Model
{
    Use HasFactory; 
    
    protected $fillable = [
        'sub_activity_id',
        'monto',
    ];

    public function subActivity(): HasOne
    {
        return $this->hasOne(SubActivity::class);
    }

}
