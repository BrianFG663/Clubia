<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Resources\PartnerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;
use Filament\Actions\Action;

class EditPartner extends EditRecord
{
    protected static string $resource = PartnerResource::class;

    public function getTitle(): string
    {
        return 'Editar socio: ' . $this->record->nombre . ' ' . $this->record->apellido;
    }

    public function mutateFormDataBeforeSave(array $data): array
{
    $telefono = '('.$data['telefono_marcacion'] . ')' . $data['telefono_caracteristica'] . '-' . $data['telefono_numero'];

    // Validar duplicado excluyendo el actual
    $idActual = $this->record->id;

    if (\App\Models\Partner::where('telefono', $telefono)->where('id', '!=', $idActual)->exists()) {
        throw ValidationException::withMessages([
            'telefono_marcacion' => ['Ya existe un socio con ese número de teléfono.'],
        ]);
    }

    $data['telefono'] = $telefono;

    return $data;
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
