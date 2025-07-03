<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use App\Filament\Resources\ActivityResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditActivity extends EditRecord
{
    protected static string $resource = ActivityResource::class;

    public function getTitle(): string
    {
        return 'Editar actividad: ' . $this->record->nombre;
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Actividad editada correctamente')
            ->success();
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Eliminar actividad')
                ->modalHeading('¿Eliminar actividad?')
                ->modalDescription('¡Esta accion no tiene vuelta atras!')
                ->successNotificationTitle('Actividad eliminada correctamente.'),
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
}
