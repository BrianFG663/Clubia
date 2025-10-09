<?php

namespace App\Filament\Pages;

use App\Models\Institution;
use Filament\Pages\Page;

class CajaDiaria extends Page
{

    public static function canAccess(): bool{
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user?->can('page_CajaDiaria') ?? false;
    }

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = '🧾Gestión Económica';
    protected static ?string $navigationLabel = 'Movimientos en caja diaria';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Registrar movimientos en caja';

    protected static string $view = 'filament.pages.caja-diaria';


    protected function getViewData(): array{

        return [
            'instituciones' => Institution::all(),
        ];

    }
}
