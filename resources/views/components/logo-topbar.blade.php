<style>
    .custom-topbar-brand {
        display: flex;
        align-items: center;
        gap: 0.5rem; /* equivalente a gap-2 */
    }

    .custom-topbar-logo {
        height: 3.8vw;
    }

    .custom-topbar-name {
        position: relative;
        right: 0.3vw;
        font-size: 1.6vw; /* text-base */
        font-weight: 600; /* font-semibold */
        color: #1f2937; /* text-gray-800 */
    }

    .dark .custom-topbar-name {
        color: #c28840; /* text-white en dark mode */
    }
</style>

<div class="custom-topbar-brand">
    <img src="{{ asset('images/logos/texturizado.png') }}" alt="Logo" class="custom-topbar-logo">
    <span class="custom-topbar-name">
        {{ config('app.name') }}
    </span>
</div>
