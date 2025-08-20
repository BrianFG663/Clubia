<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/registrar-ordendes.css')
    @vite('resources/css/sweet-alert.css')
    @vite('resources/js/registrar-ordenes.js')

    <div class="contenedor">

        <div class="filtro-container">
            <div class="input-icon-wrapper">
                <input
                type="text"
                id="filtroNombre"
                placeholder="Buscar proveedor"
                aria-label="Filtro por nombre"
                class="filtro-input"
                />
                <i class="fa-solid fa-magnifying-glass icon"></i>
            </div>
        </div>

        <div class="tabla-container">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Empleado</th>
                        <th>Proveedor</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaCuerpo">
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->nombre ?? 'Sin nombre' }}</td>
                            <td>{{ $order->supplier->nombre ?? 'Sin proveedor' }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->fecha)->format('d/m/Y') }}</td>
                            <td>${{ number_format($order->total, 2) }}</td>
                            <td class="botones">
                                <button class="btn-ver" data-order-id="{{ $order->id }}" aria-label="Ver orden {{ $order->id }}" onclick="mostrarDetallesOrden({{ $order->id }})">
                                    <i class="fa-solid fa-eye"></i>
                                </button>

                                <a href="{{ route('ordenes.pdf', $order->id) }}"  onclick="generarFactura({{ $order->id }})" target="_blank" class="btn-ver">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination-container">
                <x-filament::pagination :paginator="$orders"/>
            </div>
        </div>
    </div>


    <div id="detalle-orden" class="detalle-orden">
    <button id="cerrar-detalle" class="cerrar-detalle" aria-label="Cerrar modal">
        <i class="fa-solid fa-xmark"></i>
    </button>
    <div id="contenedor-informacion"></div>
</div>
<div id="overlay-orden"></div>



</x-filament-panels::page>

