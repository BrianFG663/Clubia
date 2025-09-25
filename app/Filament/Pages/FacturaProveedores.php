<?php

namespace App\Filament\Pages;

use App\Models\Partner;
use App\Models\Supplier;
use Filament\Pages\Page;

class FacturaProveedores extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.factura-proveedores';
    protected static ?string $navigationGroup = '🧾Facturacion';
    protected static ?string $navigationLabel = 'Facturas proveedores';
    protected static ?int $navigationSort = 5;


    public static function canAccess(): bool{
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user?->can('page_FacturaProveedores') ?? false;
    }


    public function getTitle(): string
    {
        return 'Facturas proveedores';
    }


    protected function getViewData(): array
    {
        return [
            'proveedores' => Supplier::with('orders.supplier')->get(),
        ];
    }

}