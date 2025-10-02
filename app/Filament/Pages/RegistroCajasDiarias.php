<?php

namespace App\Filament\Pages;

use App\Models\CashRecord;
use Filament\Pages\Page;
use Livewire\WithPagination;


class RegistroCajasDiarias extends Page
{

    use WithPagination;

    public static function canAccess(): bool{
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user?->can('page_RegistroCajasDiarias') ?? false;
    }

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = '📊Reportes y Registros';
    protected static ?string $navigationLabel = 'Registros de caja diaria';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Registros de caja diaria';

    protected static string $view = 'filament.pages.registro-cajas-diarias';

    

    protected function getViewData(): array
    {
        return [
            'CashRecords' => $cashRecords = CashRecord::with('cashRecordsDetails.responsable', 'institution')->orderBy('fecha', 'desc')->paginate(10)
        ];
    }
}
