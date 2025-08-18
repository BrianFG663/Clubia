<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SociosAlDia extends ChartWidget
{
    protected static ?string $heading = 'Socios que abonan en término';

    protected function getData(): array
    {
        $pagos = Payment::selectRaw("DATE_FORMAT(fecha_pago, '%Y-%m') as mes, COUNT(DISTINCT partner_id) as cantidad")
            ->whereDay('fecha_pago', '<=', 10)
            ->groupBy(DB::raw("DATE_FORMAT(fecha_pago, '%Y-%m')"))
            ->orderBy('mes')
            ->get();

        $labels = $pagos->pluck('mes')->toArray();
        $data = $pagos->pluck('cantidad')->map(fn ($v) => (int) $v)->toArray();

        return [
            'datasets' => [[
                'label' => 'Socios que abonan en término',
                'data' => $data,
                'borderColor' => 'rgba(194, 136, 64, 1)',          
                'backgroundColor' => 'rgba(194, 136, 64, 0.2)',
                'fill' => true,
                'tension' => 0.3,
            ]],
            'labels' => $labels,
        ];
    }

    // ✅ En v3, las opciones van acá:
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                        'format' => [
                            'maximumFractionDigits' => 0, // ← sin decimales
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
