<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class NotaCredito extends Page
{


    public static function canAccess(): bool{
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user?->can('page_NotaCredito') ?? false;
    }

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = '🧾Gestión Económica';
    protected static ?string $navigationLabel = 'Nota de credito';
    protected static ?int $navigationSort = 4;
    protected static ?string $title = 'Realizar una nota de credito';

    protected static string $view = 'filament.pages.nota-credito';
}
