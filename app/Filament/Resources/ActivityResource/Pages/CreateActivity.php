<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use App\Filament\Resources\ActivityResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateActivity extends CreateRecord
{
    protected static string $resource = ActivityResource::class;

    public function getTitle(): string
    {
        return 'Crear un actividad nueva.';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Actividad creada correctamente.')
            ->success();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('submit')
                ->label('Guardar actividad')
                ->submit('form'),

            Action::make('submitAndCreateAnother')
                ->label('Guardar y crear otra')
                ->submit('form')
                ->action(fn () => $this->submitAndCreateAnother()),

            Action::make('cancel')
                ->label('Cancelar registro')
                ->url($this->getRedirectUrl()),
        ];
    }

    public function submitAndCreateAnother()
    {
        $this->submit();

        Notification::make()
            ->title('Actividad creada correctamente.')
            ->success()
            ->send();

        $this->redirect($this->getRedirectUrl('create'));
    }
}
