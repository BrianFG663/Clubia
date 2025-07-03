<?php

namespace App\Filament\Resources\InstitutionResource\Pages;

use App\Filament\Resources\InstitutionResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateInstitution extends CreateRecord
{
    protected static string $resource = InstitutionResource::class;

    public function getTitle(): string
    {
        return 'Crear nueva Institucion';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Institucion creada correctamente.')
            ->success();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('submit')
                ->label('Guardar Institucion')
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
            ->title('Institución creada correctamente. Ahora puede crear otra.')
            ->success()
            ->send();

        $this->redirect($this->getRedirectUrl('create'));
    }
}
