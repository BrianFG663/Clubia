<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            Log::info("Gate::before ejecutado para {$user->email} con habilidad {$ability}");
            return $user->hasRole('super_admin') ? true : null;
        });

    }
}
