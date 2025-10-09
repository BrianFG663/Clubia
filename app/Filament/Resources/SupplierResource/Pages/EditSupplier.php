<?php

namespace App\Filament\Resources\SupplierResource\Pages;

use App\Filament\Resources\SupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditSupplier extends EditRecord
{
    protected static string $resource = SupplierResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    public function getTitle(): string
    {
        return 'Editar proveedor';
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        $telefono = '(' . $data['telefono_marcacion'] . ')' . $data['telefono_caracteristica'] . '-' . $data['telefono_numero'];

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
}
