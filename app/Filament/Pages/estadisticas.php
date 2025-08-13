<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\SociosCard;
use App\Filament\Widgets\SociosPorTipoChart;
use App\Filament\Widgets\ActividadesChart;
use Filament\Widgets\Contracts\HasWidgets;
use Filament\Widgets\Concerns\InteractsWithWidgets; 

use Filament\Pages\Page;

class estadisticas extends Page implements HasWidgets
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static string $view = 'filament.pages.estadisticas';

    public function getHeaderWidgets(): array
    {
        return [
        SociosCard::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
        SociosPorTipoChart::class,
        ActividadesChart::class,
        ];
    }
}
