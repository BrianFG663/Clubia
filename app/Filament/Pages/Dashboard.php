<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{

    public static function canAccess(): bool{
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user?->can('page_Dashboard') ?? false;
    }

    public function getTitle(): string
    {
        return '';
    }
    protected static string $view = 'filament.pages.dashboard';
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Inicio';
    protected static ?int $navigationSort = -1;
    protected static ?string $slug = 'inicio';
}
