<x-filament-panels::page.simple>

    <style>
    /* Oculta el logo por defecto */
    .fi-logo {
        display: none !important;
    }

    /* Oculta el heading original de Filament */
    .fi-simple-header-heading {
        display: none !important;
    }

    /* Estilos personalizados */
    .custom-login-header {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .custom-login-logo {
        position: relative;
        bottom: 15%;
        height: 10rem;
        margin-bottom: 1rem;
    }

    .custom-login-heading {
        color: #c28840 !important;
        position: relative;
        bottom: 14%;
        font-weight: bold;
        text-align: center;
        font-size: 2rem;
    }
</style>

    {{-- Logo + Título en orden correcto --}}
    <div class="custom-login-header">
        <img
            src="{{ asset('images/logos/texturizado.png') }}"
            alt="Logo de la app"
            class="custom-login-logo"
        >

        <div class="custom-login-heading">
            Iniciar sesión
        </div>
    </div>

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
