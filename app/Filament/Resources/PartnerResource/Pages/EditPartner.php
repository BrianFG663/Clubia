<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Resources\PartnerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditPartner extends EditRecord
{
    protected static string $resource = PartnerResource::class;

    public function getTitle(): string
    {
        return 'Editar socio: ' . $this->record->nombre . ' ' . $this->record->apellido;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Eliminar Socio')
                ->modalHeading('¿Eliminar Socio?')
                ->modalDescription('¡Esta accion no tiene vuelta atras!')
                ->successNotificationTitle('Socio eliminado correctamente.'),

            Action::make('toggleStatus')
                ->label(fn($record) => $record->state_id == 1 ? 'Pasar a inactivo' : 'Pasar a activo')
                ->color(fn($record) => $record->state_id == 1 ? 'danger' : 'success')
                ->action(function ($record) {
                    $record->state_id = $record->state_id == 1 ? 2 : 1;
                    $record->save();
                    \Filament\Notifications\Notification::make()
                        ->title('Estado actualizado correctamente.')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Guardar cambios') // Cambia el texto acá
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
