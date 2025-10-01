<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\SociosCard;
use App\Filament\Widgets\SociosPorTipoChart;
use App\Filament\Widgets\ActividadesChart;
use Filament\Pages\Page;

class Estadisticas extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static string $view = 'filament.pages.estadisticas';
}
