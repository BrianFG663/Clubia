<?php

namespace App\Filament\Resources\MemberTypeResource\Pages;

use App\Filament\Resources\MemberTypeResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateMemberType extends CreateRecord
{
    protected static string $resource = MemberTypeResource::class;

    public function getTitle(): string
    {
        return 'Crear un tipo de socio nuevo.';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Tipo de socio creado correctamente.')
            ->success();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('submit')
                ->label('Guardar tipo de socio')
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
            ->title('Tipo de socio creado correctamente.')
            ->success()
            ->send();

        $this->redirect($this->getRedirectUrl('create'));
    }
}
