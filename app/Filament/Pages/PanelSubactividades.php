<?php

namespace App\Filament\Pages;

use App\Models\SubActivity;
use Filament\Pages\Page;
use Livewire\WithPagination;

class PanelSubactividades extends Page
{
    use WithPagination;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = '📅Administracion de Actividades';
    protected static ?string $navigationLabel = 'Panel Subactividades';
    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.panel-subactividades';

    public function getTitle(): string
    {
        return 'Panel Subactividades';
    }

    protected function getViewData(): array
    {
        return [
            'subActivities' => SubActivity::withCount('partners')->paginate(10),
        ];
    }
}
