<?php

namespace App\Filament\Pages;

use App\Models\Partner;
use Filament\Pages\Page;

class ModerarFotos extends Page
{

    public static function canAccess(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user?->can('page_ModerarFotos') ?? false;
    }

    protected static ?string $navigationIcon = 'heroicon-o-eye';
    protected static ?string $title = 'Moderar de fotos carnets';
    protected static ?string $navigationLabel = 'Moderación de fotos carnets';
    protected static ?string $navigationGroup = '🔐Seguridad y Accesos';
    protected static string $view = 'filament.pages.moderar-fotos';


}
