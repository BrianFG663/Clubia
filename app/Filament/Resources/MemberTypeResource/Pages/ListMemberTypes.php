<?php

namespace App\Filament\Resources\MemberTypeResource\Pages;

use App\Filament\Resources\MemberTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMemberTypes extends ListRecords
{
    protected static string $resource = MemberTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Crear tipo de socio')
        ];
    }

    public function getTitle(): string
    {
        return 'Tipos de socio registrados';
    }
}
