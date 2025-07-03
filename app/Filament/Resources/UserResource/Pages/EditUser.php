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

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
