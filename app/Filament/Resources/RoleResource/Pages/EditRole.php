<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Illuminate\Support\Collection;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    public Collection $permissions;


    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Eliminar rol')
                ->modalHeading('¿Eliminar rol?')
                ->modalDescription('¡Esta accion no tiene vuelta atras!')
                ->successNotificationTitle('Rol eliminada correctamente.'),
        ];
    }

    public function getTitle(): string
    {
        return 'Editar rol ' . $this->record->name;
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Rol editada correctamente')
            ->success();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Actualizar datos')
                ->submit('save'),
            Action::make('cancel')
                ->label('Cancelar')
                ->url($this->getResource()::getUrl('index')),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

    protected function afterSave(): void
    {
        $permissionModels = collect();
        $this->permissions->each(function ($permission) use ($permissionModels) {
            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                'name' => $permission,
                'guard_name' => $this->data['guard_name'],
            ]));
        });

        $this->record->syncPermissions($permissionModels);
    }
}
