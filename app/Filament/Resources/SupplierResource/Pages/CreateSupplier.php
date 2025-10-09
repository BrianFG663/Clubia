<?php

namespace App\Filament\Resources\SupplierResource\Pages;

use App\Filament\Resources\SupplierResource;
use App\Models\Supplier;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class CreateSupplier extends CreateRecord
{
    protected static string $resource = SupplierResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }


    public function getTitle(): string
    {
        return 'Registrar nuevo proveedor';
    }


    public function mutateFormDataBeforeCreate(array $data): array
{
    $telefono = '(' . $data['telefono_marcacion'] . ')' . $data['telefono_caracteristica'] . '-' . $data['telefono_numero'];

    Log::info('Intentando guardar teléfono: ' . $telefono);

    if (Supplier::where('telefono', $telefono)->exists()) {
        Log::warning('Teléfono duplicado: ' . $telefono);
        throw ValidationException::withMessages([
            'telefono_marcacion' => ['Ya existe un socio con ese número de teléfono.'],
        ]);
    }

    $data['telefono'] = $telefono;

    return $data;
}

}
