<?php

namespace App\Filament\Pages;

use App\Models\Institution;
use App\Models\Parameter;
use Filament\Pages\Page;

class Parametros extends Page
{

    public static function canAccess(): bool{
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user?->can('page_Parametros') ?? false;
    }
    

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-vertical';
    protected static ?string $navigationLabel = 'Parametros';
    protected static ?string $title = 'Cambio de parametros';
    protected static ?string $navigationGroup = '⚙️Permisos del Administrador';
    protected static ?int $navigationSort = 0;
    

    protected static string $view = 'filament.pages.parametros';


    protected function getViewData(): array{

        return [
            'instituciones' => Institution::all(),
        ];

    }
}
