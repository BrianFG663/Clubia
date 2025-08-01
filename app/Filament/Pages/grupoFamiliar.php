<?php

namespace App\Filament\Pages;

use App\Models\Partner;
use Filament\Pages\Page;
use Livewire\Component;
use Livewire\WithPagination;

class grupoFamiliar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = '👥Administracion de Socios';
    protected static ?string $navigationLabel = 'Grupos familiares';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.pages.grupo-familiar';

    public function getTitle(): string
    {
        return 'Grupos familiares';
    }

    use WithPagination;


    protected function getViewData(): array
    {
        return [
            'jefes' => Partner::where('jefe_grupo', 1)
                ->with('familyMembers')
                ->paginate(5),
        ];
    }
}
