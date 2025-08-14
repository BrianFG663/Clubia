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
                            '#D4A373', // Marrón claro cálido
                            '#F4A261', // Naranja suave
                            '#E9C46A', // Amarillo mostaza apagado
                            '#A8DADC', // Verde agua desaturado
                            '#457B9D', // Azul grisáceo
                            '#E5989B', // Rosa antiguo
                            '#B5838D', // Malva cálido
                            '#CDB4DB', // Lavanda pastel
                            '#FFB4A2', // Coral suave
                            '#F6BD60', // Amarillo mantecoso
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
            'animation' => [
                'duration' => 1200, // 1.2 segundos
                'easing' => 'easeOutQuart', // Curva suave y elegante
            ],
            'hover' => [
                'animationDuration' => 400, // Animación al pasar el mouse
            ],
            'responsiveAnimationDuration' => 800, // Transición al redimensionar
            'scales' => [
                'y' => [
                    'ticks' => [
                        'precision' => 0,
                        'stepSize' => 1,
                        'beginAtZero' => true,
                        'font' => [
                            'size' => 13,
                            'weight' => '500',
                        ],
                    ],
                    'grid' => [
                        'color' => 'rgba(0,0,0,0.05)', // Líneas suaves
                    ],
                ],
                'x' => [
                    'ticks' => [
                        'font' => [
                            'size' => 13,
                            'weight' => '500',
                        ],
                    ],
                    'grid' => [
                        'display' => false, // Sin líneas verticales
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false, // Ocultamos leyenda si no es necesaria
                ],
                'tooltip' => [
                    'enabled' => true,
                    'backgroundColor' => '#333',
                    'titleFont' => [
                        'size' => 14,
                        'weight' => '600',
                    ],
                    'bodyFont' => [
                        'size' => 13,
                    ],
                    'cornerRadius' => 4,
                    'padding' => 10,
                ],
            ],
        ];
    }

}
