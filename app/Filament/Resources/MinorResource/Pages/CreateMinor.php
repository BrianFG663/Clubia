<?php

namespace App\Filament\Resources\MinorResource\Pages;

use App\Filament\Resources\MinorResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateMinor extends CreateRecord
{
    protected static string $resource = MinorResource::class;

    public function getTitle(): string
    {
        return 'Nuevo Socio Menor';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Socio menor correctamente.')
            ->success();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('submit')
                ->label('Guardar menor')
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
            ->title('Socio menor creado correctamente. Ahora puede crear otro.')
            ->success()
            ->send();

        $this->redirect($this->getRedirectUrl('create'));
    }
}
