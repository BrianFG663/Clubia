<style>
    .custom-topbar-brand {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .custom-topbar-logo {
        height: 3.8vw;
    }

    .custom-topbar-name {
        position: relative;
        right: 0.3vw;
        font-size: 1.6vw;
        font-weight: 600;
        color: #1f2937;
    }

    .dark .custom-topbar-name {
        color: #c28840;
    }
</style>

<div class="custom-topbar-brand">
    @php
        $ruta = 'imagenes/logo.png';
        $logo = Illuminate\Support\Facades\Storage::disk('public')->exists($ruta)
            ? asset("storage/$ruta") . '?v=' . filemtime(public_path("storage/$ruta"))
            : null;
    @endphp

    @if ($logo)
        <img src="{{ $logo }}" alt="Logo actual" class="custom-topbar-logo">
    @endif
    <span class="custom-topbar-name">
        {{ config('app.name') }}
    </span>
</div>
