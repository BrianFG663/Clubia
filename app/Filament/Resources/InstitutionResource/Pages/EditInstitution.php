<?php

namespace App\Filament\Resources\InstitutionResource\Pages;

use App\Filament\Resources\InstitutionResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditInstitution extends EditRecord
{
    protected static string $resource = InstitutionResource::class;

    public function getTitle(): string
    {
        return 'Editar Institución: ' . $this->record->nombre;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Eliminar Institucion')
                ->modalHeading('¿Eliminar Institucion?')
                ->modalDescription('¡Esta accion no tiene vuelta atras!')
                ->successNotificationTitle('Institucion eliminada correctamente.'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Guardar cambios')
                ->submit('save'),
            Action::make('cancel')
                ->label('Cancelar')
                ->url($this->getResource()::getUrl('index')),
        ];
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        $telefono = '(' . $data['telefono_marcacion'] . ')' . $data['telefono_caracteristica'] . '-' . $data['telefono_numero'];

        // Validar duplicado excluyendo el actual
        $idActual = $this->record->id;

        if (\App\Models\Institution::where('telefono', $telefono)->where('id', '!=', $idActual)->exists()) {
            throw ValidationException::withMessages([
                'telefono_marcacion' => ['Ya existe un socio con ese número de teléfono.'],
            ]);
        }

        $data['telefono'] = $telefono;

        return $data;
    }
}
