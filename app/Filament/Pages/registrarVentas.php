<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class registrarVentas extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.registrar-ventas';
    protected static ?string $navigationGroup = '🛒Administracion de Ventas';    
        protected static ?int $navigationSort = 6;

}
