<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    Use HasFactory;
    
   protected $fillable = [
    'institution_id',
    'client_id',
    'order_id',
    'sub_activity_id',
    'member_type_id',
    'tipo_factura',
    'sale_id',
    'monto_total',
    'fecha_factura',
    'estado_pago', 
];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'client_id');
    }

    public function institution():  BelongsTo
    {
        return $this-> BelongsTo(Institution::class);
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function memberType(): BelongsTo
    {
        return $this->belongsTo(MemberType::class);
    }

    public function subActivity(): BelongsTo
    {
        return $this->belongsTo(SubActivity::class);
    }

    }
