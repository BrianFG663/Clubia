<x-filament-panels::page.simple>

    {{-- Título personalizado --}}
    <x-slot name="heading">
    <div style="color: #c28840 !important;" class="font-bold text-center">
        Iniciar sesión
    </div>
</x-slot>



    {{-- Subtítulo opcional --}}
    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}
            {{ $this->registerAction }}
        </x-slot>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(
        \Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
        scopes: $this->getRenderHookScopes()
    ) }}

    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        <button
            type="submit"
            class="filament-button w-full mt-4 bg-primary-600 text-white font-semibold py-2 px-4 rounded hover:bg-primary-700 transition"
        >
            Acceder
        </button>
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(
        \Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
        scopes: $this->getRenderHookScopes()
    ) }}
</x-filament-panels::page.simple>
