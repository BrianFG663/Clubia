<?php

namespace App\Filament\Pages;

use App\Models\SubActivity;
use Filament\Pages\Page;
use Livewire\WithPagination;

class PanelSubactividades extends Page
{
    use WithPagination;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = '🧍Socios y Actividades';
    protected static ?string $navigationLabel = 'Panel socios-subactividades';
    protected static ?int $navigationSort = 5;

    public static function canAccess(): bool{
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user?->can('page_PanelSubactividades') ?? false;
    }

    protected static string $view = 'filament.pages.panel-subActividades';

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
