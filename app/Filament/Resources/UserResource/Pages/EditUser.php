<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public bool $isActive;

    public function mount($record): void
    {
        parent::mount($record);
        $this->isActive = $this->record->state_id == 1;
    }

    public function getTitle(): string
    {
        return 'Editar Empleado: ' . $this->record->nombre . ' ' . $this->record->apellido;
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Empleado editado correctamente')
            ->success();
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Eliminar Empleado')
                ->modalHeading('¿Eliminar Empleado?')
                ->modalDescription('¡Esta accion no tiene vuelta atras!')
                ->successNotificationTitle('Empleado eliminado correctamente.'),
        ];
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

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $addRoleId = $this->form->getState()['add_role'] ?? null;
        $removeRoleId = $this->form->getState()['remove_role'] ?? null;

        if ($addRoleId) {
            $role = \Spatie\Permission\Models\Role::find($addRoleId);
            if ($role && !$this->record->hasRole($role)) {
                $this->record->assignRole($role);
            }
        }

        if ($removeRoleId) {
            $role = \Spatie\Permission\Models\Role::find($removeRoleId);
            if ($role && $this->record->hasRole($role)) {
                $this->record->removeRole($role);
            }
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['add_role'] = null;
        $data['remove_role'] = null;

        return $data;
    }
}
