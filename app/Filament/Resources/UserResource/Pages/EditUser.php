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
        return 'Editar Usuario: ' . $this->record->nombre . ' ' . $this->record->apellido;
    }

    protected function getSavedNotification(): ?Notification
{
    return Notification::make()
        ->title('Socio editado correctamente')
        ->success();
}

    protected function getHeaderActions(): array
{
    return [
        Action::make('toggleState')
            ->label(fn () => $this->isActive ? 'Pasar a Inactivo' : 'Pasar a Activo')
            ->color(fn () => $this->isActive ? 'danger' : 'success')
            ->icon(fn () => $this->isActive ? 'heroicon-o-user-minus' : 'heroicon-o-user-plus')
            ->action(function () {
                $nuevoEstado = $this->isActive ? 2 : 1;

                $this->record->update([
                    'state_id' => $nuevoEstado,
                ]);

                $this->isActive = !$this->isActive;

                Notification::make()
                    ->title($nuevoEstado === 1 ? 'Usuario activado' : 'Usuario inactivado')
                    ->success()
                    ->send();
            }),

        DeleteAction::make()
            ->label('Eliminar socio')
            ->modalHeading('¿Eliminar socio?')
            ->modalDescription('¡Esta accion no tiene vuelta atras!')
            ->successNotificationTitle('Socio eliminado correctamente.'),
    ];
}

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
