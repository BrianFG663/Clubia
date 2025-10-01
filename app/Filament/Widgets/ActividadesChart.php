<?php

namespace App\Filament\Widgets;

use App\Models\Activity;
use Filament\Widgets\ChartWidget;

class ActividadesChart extends ChartWidget
{
    protected static ?string $heading = 'Socios por Actividad';

    protected function getData(): array
    {
        $activities = Activity::with('subActivities.partners')->get();

        $labels = [];
        $data = [];

        foreach ($activities as $activity) {
            $labels[] = $activity->nombre;

            // Obtener todos los partner_id únicos asociados a las subactividades de esta actividad
            $partnerIds = $activity->subActivities
                ->flatMap(function ($subActivity) {
                    return $subActivity->partners->pluck('id');
                })
                ->unique();

            $data[] = $partnerIds->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cantidad de socios',
                    'data' => $data,
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
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'animation' => [
                'duration' => 1200,
                'easing' => 'easeOutQuart', // Más suave y profesional
                'animateScale' => true,
                'animateRotate' => false, // Evita el giro brusco
            ],
            'scales' => [
                'y' => [
                    'ticks' => [
                        'display' => false,
                    ],
                    'grid' => [
                        'color' => 'rgba(0,0,0,0.05)', // Líneas suaves
                    ],
                ],
                'x' => [
                    'ticks' => [
                        'display' => false,
                    ],
                    'grid' => [
                        'display' => false, // Sin líneas verticales
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 12,
                        'padding' => 15,
                        'font' => [
                            'size' => 14,
                            'weight' => '500',
                        ],
                    ],
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

    protected function getType(): string
    {
        return 'pie';
    }
}
