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
    <img src="{{ asset('images/logos/texturizado.png') }}" alt="Logo" class="custom-topbar-logo">
    <span class="custom-topbar-name">
        {{ config('app.name') }}
    </span>
</div>
