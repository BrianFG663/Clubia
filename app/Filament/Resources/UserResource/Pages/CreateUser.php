<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return 'Crear un empleado nuevo.';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Empleado creado correctamente.')
            ->success();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('submit')
                ->label('Guardar empleado')
                ->submit('form'),

            Action::make('submitAndCreateAnother')
                ->label('Guardar y crear otro')
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
            ->title('Empleado creado correctamente. Ahora puede crear otro.')
            ->success()
            ->send();

        $this->redirect($this->getRedirectUrl('create'));
    }
}
