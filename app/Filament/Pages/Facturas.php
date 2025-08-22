<?php

namespace App\Filament\Pages;

use App\Models\Institution;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Facturas extends Page
{

    public static function canAccess(): bool{
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user?->can('page_Facturas') ?? false;
    }

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
