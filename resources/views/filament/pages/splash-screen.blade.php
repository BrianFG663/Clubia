<x-filament-panels::page>
    @vite('resources/css/splash.css') 

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-[#030712] to-[#111827] relative overflow-hidden">
        
        <div class="absolute inset-y-0 left-0 w-1/2 opacity-20 bg-no-repeat bg-cover bg-left" style="background-image: url('{{ asset('images/splash-bg.jpg') }}');"></div>

        <div class="z-10 max-w-xl text-center px-6">
            <h1 class="text-3xl font-bold text-[#bd8540] mb-4">Clubia</h1>
            <p class="text-gray-300 mb-6">
                Clubia es una plataforma de gestión inteligente desarrollada para unificar todas las operaciones de un club en un solo sistema. Desde la administración de socios hasta la facturación, pasando por actividades, institutos y personal, todo lo que antes era un desafío, hoy está a tu alcance.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('admin') }}" class="px-6 py-3 rounded-md bg-[#af7a3a] text-white font-semibold hover:bg-[#bd8540] transition">Información del socio</a>
                <a href="{{ route('login') }}" class="px-6 py-3 rounded-md bg-[#af7a3a] text-white font-semibold hover:bg-[#bd8540] transition">Iniciar sesión</a>
            </div>
        </div>
    </div>
</x-filament-panels::page>
