<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/registrar-ventas.css')
    @vite('resources/js/registrar-ventas.js')

    <div class="contenedor-general space-y-6">

        <!-- Campo de búsqueda -->
        <div class="rounded-xl p-4 bg-black dark:bg-black shadow-sm h-48">
            <input type="text" placeholder="Buscar producto..."
               autocomplete="off" id="buscar" class="input-busqueda px-4 py-2 rounded border border-primary bg-white text-black placeholder-black/60 dark:bg-black dark:text-white dark:placeholder-white focus:outline-none focus:ring-2 focus:ring-primary" />
            <div id="resultados" class="resultados "></div>
        </div>

        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Carrito con artículos seleccionados:</h2>
        <!-- Lista de artículos seleccionados -->
        <div id="carrito" class="lista"><div class="carrito-vacio"><img class="img-vacio" src="{{asset('images/html/caja-vacia.png')}}" alt=""><span>Aun no se seleccionaron productos</span></div></div>

        <!-- Botón para registrar venta -->
        <div class="text-center">
            <button id="enviar-carrito" class="btn-registrar px-6 py-2 rounded-lg bg-primary-600 text-white hover:bg-primary-700 transition">Registrar Venta</button>
        </div>
        <div id="total-carrito" class="total-carrito">TOTAL: $00.00</div>
    </div>
</x-filament-panels::page>
