<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    public Collection $permissions;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->permissions = collect($data)
            ->filter(function ($permission, $key) {
                return ! in_array($key, ['name', 'guard_name', 'select_all', Utils::getTenantModelForeignKey()]);
            })
            ->values()
            ->flatten()
            ->unique();

        if (Arr::has($data, Utils::getTenantModelForeignKey())) {
            return Arr::only($data, ['name', 'guard_name', Utils::getTenantModelForeignKey()]);
        }

        return Arr::only($data, ['name', 'guard_name']);
    }

    protected function afterCreate(): void
    {
        $this->syncAllPermissions();
    }

    protected function syncAllPermissions(): void
    {
        $permissionModels = collect();

        // permisos elegidos en el form
        $this->permissions->each(function ($permission) use ($permissionModels) {
            $permissionModels->push(
                Utils::getPermissionModel()::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => $this->data['guard_name'],
                ])
            );
        });

        // permisos extra automáticos
        $extraPermissions = ['access_admin_panel', 'access_users_panel'];

        foreach ($extraPermissions as $perm) {
            $permissionModels->push(
                Utils::getPermissionModel()::firstOrCreate([
                    'name' => $perm,
                    'guard_name' => $this->data['guard_name'],
                ])
            );
        }

        // sincronizar todo
        $this->record->syncPermissions($permissionModels);
    }
}
