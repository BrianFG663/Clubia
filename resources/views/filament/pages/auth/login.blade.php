<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite('resources/css/app.css') {{-- Si usás Tailwind --}}
</head>

<body class="bg-gray-700 flex items-center justify-center min-h-screen">

    <div class="bg-black-900 p-8 rounded shadow-md w-full max-w-md">
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16">
        </div>

        <form wire:submit.prevent="authenticate" class="space-y-6">
            {{ $this->form }}

            <x-filament::button type="submit" class="w-full">
                Iniciar sesión
            </x-filament::button>
        </form>
    </div>

</body>

</html>
