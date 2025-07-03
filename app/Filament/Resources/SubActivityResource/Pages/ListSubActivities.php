<?php

namespace App\Filament\Resources\SubActivityResource\Pages;

use App\Filament\Resources\SubActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubActivities extends ListRecords
{
    protected static string $resource = SubActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
