<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/moderarFotos.css')
    @vite('resources/css/sweet-alert.css')
    @vite('resources/js/moderarFotos.js')

    <div class="mensaje" id="mensaje">
        <img id="icono-mensaje" class="imagen-mensaje" src="" alt="Estado">
        <span id="texto-mensaje" class="texto-mensaje"></span>
    </div>

    <div class="contenedor" id="contenedor"></div>

</x-filament-panels::page>
