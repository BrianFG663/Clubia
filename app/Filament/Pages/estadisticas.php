<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\SociosCard;
use App\Filament\Widgets\SociosPorTipoChart;

use Filament\Pages\Page;

class estadisticas extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static string $view = 'filament.pages.estadisticas';

    public function getHeaderWidgets(): array
    {
        return [
        SociosCard::class,
        SociosPorTipoChart::class,
        ];
    }
}
