<?php

namespace App\Filament\Widgets;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\ChartWidget;
use App\Models\Activity;
use Filament\Forms;

class ActividadSubactividadesChart extends ChartWidget implements HasForms
{
    
    use InteractsWithForms;

    protected static ?string $heading = 'Elija una Actividad';

    public ?int $actividadId = null;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('actividadId')
                ->label('Selecciona una Actividad')
                ->options(Activity::pluck('nombre', 'id'))
                ->searchable()
                ->reactive(),
        ];
    }

    
    protected function getData(): array
    {
        if (! $this->actividadId) {
            return [
                'datasets' => [],
                'labels'   => [],
            ];
        }

        $actividad = Activity::with('subActivities.partners')->find($this->actividadId);

        $labels = $actividad->subActivities->pluck('nombre')->toArray();
        $data   = $actividad->subActivities->map(fn ($sub) => $sub->partners->count())->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Cantidad de socios',
                    'data'  => $data,
                    'backgroundColor' => '#3b82f6', // azul Tailwind
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getContent(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.widgets.activity-subactivities-form', [
            'form' => $this->form,
        ]);
    }

    
}
