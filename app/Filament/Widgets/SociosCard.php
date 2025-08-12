<?php

namespace App\Filament\Widgets;

use App\Models\Partner;
use Filament\Widgets\Widget;

class SociosCard extends Widget
{
    protected static string $view = 'filament.widgets.socios-card';

    public function getData(): array
    {
        return [
            'totalSocios' => Partner::count(),
        ];
    }
}
