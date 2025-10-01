 <?php
/*
namespace App\Filament\Widgets;

use Filament\Forms;
use App\Models\Activity;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class ActividadSubactividadesChart extends ChartWidget implements HasForms
{
    use InteractsWithForms;

    protected static ?string $heading = 'Subactividades por Actividad';
    protected int | string | array $columnSpan = 'full';

    public ?int $actividadId = null;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('actividadId')
                ->label('Selecciona una Actividad')
                ->options(Activity::pluck('nombre', 'id'))
                ->searchable()
                ->reactive()
                ->afterStateUpdated(fn () => $this->dispatchChartRefresh()),
        ];
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function dispatchChartRefresh(): void
    {
        // Esto fuerza a que el gráfico se actualice
        $this->dispatch('refreshChart');
    }

    protected function getData(): array
    {
        if (! $this->actividadId) {
            return [
                'datasets' => [
                    [
                        'label' => 'Cantidad de socios',
                        'data' => [],
                        'backgroundColor' => '#3b82f6',
                    ],
                ],
                'labels' => [],
            ];
        }

        $actividad = Activity::with('subActivities.partners')->find($this->actividadId);

        if (! $actividad) {
            return [
                'datasets' => [
                    [
                        'label' => 'Cantidad de socios',
                        'data' => [],
                        'backgroundColor' => '#3b82f6',
                    ],
                ],
                'labels' => [],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cantidad de socios',
                    'data' => $actividad->subActivities->map(fn ($sub) => $sub->partners->count())->toArray(),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $actividad->subActivities->pluck('nombre')->toArray(),
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
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
*/
