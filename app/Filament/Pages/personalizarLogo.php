<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class personalizarLogo extends Page
{

    public static function canAccess(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user?->can('page_personalizarLogo') ?? false;
    }

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'Personalizar logo de aplicacion';
    protected static ?string $title = 'Personalizar logo de aplicacion';
    protected static ?string $navigationGroup = '🔐Seguridad y Accesos';
    protected static string $view = 'filament.pages.personalizar-logo';
}
