<?php

namespace App\Filament\Widgets;

use App\Models\Activity;
use Filament\Widgets\ChartWidget;

class ActividadesChart extends ChartWidget
{
    protected static ?string $heading = 'Socios por Actividad';

    protected function getData(): array
    {
        // Obtener todas las actividades con la cantidad de socios únicos
        $activities = Activity::with(['subActivities.partners'])
            ->get()
            ->map(function ($activity) {
                // Contar socios únicos para esta actividad
                $totalSocios = $activity->subActivities
                    ->flatMap->partnerSubActivities
                    ->pluck('partner_id')
                    ->unique()
                    ->count();

                return [
                    'nombre' => $activity->nombre,
                    'total' => $totalSocios,
                ];
            });

        return [
            'datasets' => [
                [
                    'label' => 'Cantidad de socios',
                    'data' => $activities->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ],
                ],
            ],
            'labels' => $activities->pluck('nombre')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
