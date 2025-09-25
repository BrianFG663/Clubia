<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            $knownAbilities = Permission::pluck('name')->toArray();

            if (in_array($ability, $knownAbilities)) {
                return $user->hasRole('super_admin') ? true : null;
            }

            return null;
        });


    }
}
