<?php

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Livewire\WithPagination;

class Ordenes extends Page
{
    use WithPagination;

    public static function canAccess(): bool{
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user?->can('page_Ordenes') ?? false;
    }

    protected static ?string $navigationIcon = 'heroicon-o-numbered-list';
    protected static string $view = 'filament.pages.orders.ordenes';
    protected static ?string $navigationLabel = 'Listado y facturación - Órdenes de compra';
    protected static ?string $title = 'Listado y facturación - Órdenes de compra';
    protected static ?string $navigationGroup = '📦Ordenes de compra';
    protected static ?int $navigationSort = 9;
    


    protected function getViewData(): array
{
    return [
        'orders' => Order::with(['user', 'supplier', 'orderDetails'])->paginate(10),
    ];
}

}