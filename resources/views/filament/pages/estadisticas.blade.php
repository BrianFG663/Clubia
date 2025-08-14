<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-2">
        @livewire(\App\Filament\Widgets\SociosCard::class)
        @livewire(\App\Filament\Widgets\SociosPorTipoChart::class)
        @livewire(\App\Filament\Widgets\ActividadesChart::class)
    </div>
</x-filament-panels::page>
