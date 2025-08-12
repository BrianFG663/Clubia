<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/registrar-ventas.css')
    @vite('resources/css/sweet-alert.css')
    @vite('resources/js/registrar-ventas.js')

    <div class="contenedor-general space-y-6">

        <!-- Campo de búsqueda -->
        <div class="contenedor-busqueda">
            <input type="text" placeholder="Buscar producto..."
               autocomplete="off" id="buscar" class="input-busqueda" />
            <div id="resultados" class="resultados "></div>
        </div>

        <h2 class="titulo">Carrito con artículos seleccionados:</h2>
        <!-- Lista de artículos seleccionados -->
        <div id="carrito" class="lista"><div class="carrito-vacio"><img class="img-vacio" src="{{asset('images/html/caja-vacia.png')}}" alt=""><span>Aun no se seleccionaron productos</span></div></div>

        <!-- Botón para registrar venta -->
        <div class="text-center">
            <button id="enviar-carrito" class="btn-registrar">Registrar Venta</button>
        </div>
        <div id="total-carrito" class="total-carrito">TOTAL: $00.00</div>
    </div>
</x-filament-panels::page>
