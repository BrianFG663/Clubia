<?php

namespace App\Filament\Pages;

use App\Models\Institution;
use App\Models\Invoice;
use App\Models\Partner;
use Filament\Pages\Page;
use Livewire\WithPagination;

class cobroFacturas extends Page
{

    use WithPagination;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = '🧾Facturacion';
    protected static ?string $navigationLabel = 'Estados y cobro';
    protected static ?int $navigationSort = 5;
    protected static ?string $title = 'Estados y cobro';

    protected static string $view = 'filament.pages.cobro-facturas';


protected function getViewData(): array
{
    $partners = Partner::withCount(['facturasImpagas', 'facturasPagas'])
    ->where(function ($q) {
        $q->whereNull('responsable_id')   
          ->orWhere('jefe_grupo', true); 
    })
    ->paginate(10);

    return [
        'partners' => $partners,
    ];
}

}