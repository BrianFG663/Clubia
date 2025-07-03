<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Resources\PartnerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditPartner extends EditRecord
{
    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Eliminar Socio')
                ->modalHeading('¿Eliminar Socio?')
                ->modalDescription('¡Esta accion no tiene vuelta atras!')
                ->successNotificationTitle('Socio eliminado correctamente.'),
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
}
