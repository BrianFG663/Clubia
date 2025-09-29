<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Resources\PartnerResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use Filament\Resources\Pages\CreateRecord;


class CreatePartner extends CreateRecord
{
    protected static string $resource = PartnerResource::class;

    public function getTitle(): string
    {
        return 'Crear nuevo socio';
    }

public function mutateFormDataBeforeCreate(array $data): array
{
    $telefono = '('.$data['telefono_marcacion'] . ')' . $data['telefono_caracteristica'] . '-' . $data['telefono_numero'];

    // Validar duplicado
    if (\App\Models\Partner::where('telefono', $telefono)->exists()) {
        throw ValidationException::withMessages([
            'telefono_marcacion' => ['Ya existe un socio con ese número de teléfono.'],
        ]);
    }

    $data['telefono'] = $telefono;

    // Lógica del responsable
    if (!empty($data['responsable_id'])) {
        $responsable = \App\Models\Partner::find($data['responsable_id']);
        if ($responsable && !$responsable->jefe_grupo) {
            $responsable->jefe_grupo = true;
            $responsable->save();
        }
    }

    return $data;
}


    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Socio creado correctamente.')
            ->success();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('submit')
                ->label('Guardar socio')
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
            ->title('Socio creado correctamente. Ahora puede crear otro.')
            ->success()
            ->send();

        $this->redirect($this->getRedirectUrl('create'));
    }
}
