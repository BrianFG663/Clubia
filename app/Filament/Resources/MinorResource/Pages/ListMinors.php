<?php

namespace App\Filament\Resources\MinorResource\Pages;

use App\Filament\Resources\MinorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMinors extends ListRecords
{
    protected static string $resource = MinorResource::class;

    protected function getHeaderActions(): array
{
    return [
        Actions\CreateAction::make()
        ->label('Crear Socio Menor')
    ];
}

    public function getTitle(): string
    {
        return 'Socios (-18) registrados';
    }

}
