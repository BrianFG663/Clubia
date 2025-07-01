<?php

namespace App\Filament\Resources\MinorResource\Pages;

use App\Filament\Resources\MinorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMinor extends EditRecord
{
    protected static string $resource = MinorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Eliminar socio menor')
            ->modalHeading('¿Eliminar socio menor?')
            ->modalDescription('¡Esta accion no tiene vuelta atras!')
            ->successNotificationTitle('Socio menor eliminado correctamente.')
        ];
    }

    public function getTitle(): string
    {
        return 'Editar Usuario: ' . $this->record->nombre . ' ' . $this->record->apellido;
    }
}
