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
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'
                    ],
                ],
            ],
            'labels' => $tiposConCantidad->pluck('nombre')->toArray(), // O el campo que uses como nombre
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
