<?php

namespace App\Filament\Pages;

use App\Models\Institution;
use App\Models\Invoice;
use App\Models\Partner;
use Filament\Pages\Page;
use Livewire\WithPagination;

class cobroFacturas extends Page{

    use WithPagination;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = '🧾Gestión Económica';
    protected static ?string $navigationLabel = 'Estado de Cuenta del Socio';
    protected static ?int $navigationSort = 5;
    protected static ?string $title = 'Estado de Cuenta del Socio';

    protected static string $view = 'filament.pages.cobro-facturas';

    public static function canAccess(): bool{
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user?->can('page_cobroFacturas') ?? false;
    }


    protected function getViewData(): array{

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