<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class RegistrarVentas extends Page
{

    public static function canAccess(): bool{
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user?->can('page_registrarVentas') ?? false;
    }

    protected static ?string $permission = 'view_registrar_ventas';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static string $view = 'filament.pages.registrar-ventas';
    protected static ?string $navigationGroup = '🛒Administracion de Ventas';    
    protected static ?string $navigationLabel = 'Realizar ventas';
    protected static ?int $navigationSort = 6;

}
