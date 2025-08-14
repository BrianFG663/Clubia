<?php

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Livewire\WithPagination;

class Ordenes extends Page
{
    use WithPagination;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string $view = 'filament.pages.orders.ordenes';
    protected static ?string $navigationLabel = 'Órdenes (Vista personalizada)';


    protected function getViewData(): array
{
    return [
        'orders' => Order::with(['user', 'supplier', 'orderDetails'])->paginate(10),
    ];
}

}