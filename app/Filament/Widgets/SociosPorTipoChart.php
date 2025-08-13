<?php

namespace App\Filament\Widgets;

use App\Models\MemberType;
use Filament\Widgets\ChartWidget;

class SociosPorTipoChart extends ChartWidget
{
    protected static ?string $heading = 'Tipos de Socios Actuales';

    protected function getData(): array
    {
         $tiposConCantidad = MemberType::withCount('partners')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Cantidad de socios',
                    'data' => $tiposConCantidad->pluck('partners_count')->toArray(),
                    'backgroundColor' => [
                       '#FF6B35', '#FFB627', '#E85D04', '#D00000', '#F48C06', '#9D0208' 
                    ],
                ],
            ],
            'labels' => $tiposConCantidad->pluck('nombre')->toArray(), 
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'ticks' => [
                        'precision' => 0, // Esto fuerza a que no tenga decimales
                        'stepSize' => 1,  // Paso entre cada valor del eje Y
                        'beginAtZero' => true,
                    ],
                ],
            ],
        ];
    }
}
