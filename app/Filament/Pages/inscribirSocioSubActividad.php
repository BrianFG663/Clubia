<?php

namespace App\Filament\Pages;

use App\Models\Activity;
use App\Models\Partner;
use Livewire\WithPagination;
use Filament\Pages\Page;

class inscribirSocioSubActividad extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationGroup = '👥Administracion de Socios';
    protected static ?string $navigationLabel = 'Inscribir socio a sub actividad';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.pages.inscribir-socio-sub-actividad';

    public function getTitle(): string
    {
        return 'Inscribir socio a sub actividad';
    }

    use WithPagination;

    protected function getViewData(): array
    {
        return [
            'socios' => Partner::all(),
            'actividades'=> Activity::with('subActivities')->get(),
        ];
    }
}
