<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/personalizarLogo.css')
    @vite('resources/css/sweet-alert.css')

    <div class="contenedor">
        <form class="formulario" action="{{ route('imagen.guardar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="imagen">
            <button class="boton" type="submit">Subir imagen</button>
        </form>


        @php
            $ruta = 'imagenes/logo.png';
            $logo = Illuminate\Support\Facades\Storage::disk('public')->exists($ruta)
                ? asset("storage/$ruta") . '?v=' . filemtime(public_path("storage/$ruta"))
                : null;
        @endphp

        @if ($logo)
            <div class="contenedor-imagen">
                <span class="titulo-no-logo">Logo actual del sistema:</span>
                <img src="{{ $logo }}" alt="Logo actual">
            </div>
        @else
            <div class="contenedor-imagen">
                <span class="titulo-no-logo">No se encontró ningún logo. Por favor, cargue uno nuevo.</span>
                <img src="{{ asset('images/logos/no-logo.png') }}" alt="Sin logo disponible">
            </div>
        @endif

        @if (session('mensaje'))
            <div class="mensaje-cambiado">
                {{ session('mensaje') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mensaje-error">
                {{ session('error') }}
            </div>
        @endif

    </div>




</x-filament-panels::page>
