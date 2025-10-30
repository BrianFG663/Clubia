<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Partner extends Authenticatable implements HasMedia
{
    use HasFactory;
    use HasRoles;
    use InteractsWithMedia;

    

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

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('profile')
            ->fit(Fit::Crop, 300, 300)
            ->nonQueued();
    }


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

    public function paymentLinks()
    {
        return $this->hasMany(PaymentLink::class);
    }


}
