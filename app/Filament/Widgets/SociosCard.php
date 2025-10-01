<?php

namespace App\Filament\Widgets;

use App\Models\Partner;
use Filament\Widgets\Widget;

class SociosCard extends Widget
{
    protected static string $view = 'filament.widgets.socios-card';

    public int $totalSocios = 0;

    public function mount(): void
    {
        $this->totalSocios = Partner::whereHas('state', fn($q) => $q->where('nombre', 'Activo'))->count();
    }
}
