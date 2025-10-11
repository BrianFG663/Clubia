<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Partner extends Authenticatable
{
    use HasFactory;
    use HasRoles;

    protected $guard_name = 'partner';

    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'state_id',
        'fecha_nacimiento',
        'direccion',
        'ciudad',
        'telefono',
        'email',
        'menor',
        'jefe_grupo',
        'responsable_id'
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'client_id');
    }
    public function memberTypes(): BelongsToMany
    {
        return $this->belongsToMany(MemberType::class);
    }
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
    public function subActivities(): BelongsToMany
    {
        return $this->belongsToMany(SubActivity::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'responsable_id'); 
    }   

    public function familyMembers(): HasMany
    {
        return $this->hasMany(Partner::class, 'responsable_id');
    }

    //relacion filtradas de invoices
    public function facturasImpagas()
    {
        return $this->hasMany(Invoice::class, 'client_id')->where('estado_pago', false);
    }

    public function facturasPagas()
    {
        return $this->hasMany(Invoice::class, 'client_id')->where('estado_pago', true);
    }

}
