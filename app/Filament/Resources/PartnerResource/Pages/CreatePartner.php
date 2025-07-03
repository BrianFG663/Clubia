<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Resources\PartnerResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;


class CreatePartner extends CreateRecord
{
    protected static string $resource = PartnerResource::class;

    public function getTitle(): string
    {
        return 'Creae nuevo socio';
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
        // Guardamos el formulario
        $this->submit();

        Notification::make()
            ->title('Empleado creado correctamente. Ahora puede crear otro.')
            ->success()
            ->send();

        $this->redirect($this->getRedirectUrl('create'));
    }
}
