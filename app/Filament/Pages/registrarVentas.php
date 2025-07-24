<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class registrarVentas extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static string $view = 'filament.pages.registrar-ventas';
    protected static ?string $navigationGroup = '🛒Administracion de Ventas';    
    protected static ?string $navigationLabel = 'Realizar ventas';
    protected static ?int $navigationSort = 6;

}
