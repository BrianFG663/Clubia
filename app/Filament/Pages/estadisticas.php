<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\SociosCard;
use App\Filament\Widgets\SociosPorTipoChart;
use App\Filament\Widgets\ActividadesChart;
use Filament\Pages\Page;

class Estadisticas extends Page
{

    public static function canAccess(): bool{
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user?->can('page_Estadisticas') ?? false;
    }

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static string $view = 'filament.pages.estadisticas';  
    protected static ?string $navigationLabel = 'Estadisticas';
    protected static ?int $navigationSort = 5;
}
