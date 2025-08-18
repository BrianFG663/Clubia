<?php

namespace App\Filament\Pages;

use App\Models\Institution;
use Filament\Pages\Page;

class Facturas extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = '🧾Facturacion';
    protected static ?string $navigationLabel = 'Facturación Mensual';
    protected static ?int $navigationSort = 4;
    protected static ?string $title = 'Facturación mensual de socios';


    protected static string $view = 'filament.pages.facturas';

    protected function getViewData(): array{

        return [
            'instituciones' => Institution::all(),
        ];

    }
    
}
