<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/panel-subactividades.css')
    @vite('resources/css/sweet-alert.css')
    @vite('resources/js/panel-subactividades.js')


    <div class="contenedor">

         <div class="filtro-container">
            <div class="input-icon-wrapper">
                <input
                type="text"
                id="filtroNombre"
                placeholder="Buscar Subactividad"
                aria-label="Filtro por nombre"
                class="filtro-input"
                />
                <i class="fa-solid fa-magnifying-glass icon"></i>
            </div>
        </div>

        <div id="detalle-socios" class="detalle-socios">
            <button id="cerrar-detalle" class="cerrar-detalle"><i class="fa-solid fa-xmark" ></i></button>
            <div id="contenedor-informacion"></div>
        </div>
        <div id="overlay-socios"></div>


        <table class="tabla table-auto w-full">
            <thead>
                <tr>
                    <th>Actividad</th>
                    <th>Subactividad</th>
                    <th>Precio</th>
                    <th>Cantidad de socios</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaCuerpo">
                @foreach ($subActivities as $sub)

                    <tr>
                        <td>{{ $sub->activity->nombre }}</td>
                        <td>{{ $sub->nombre }}</td>
                        <td>{{ $sub->monto  }}</td>
                        <td style="text-align: center;">{{ $sub->partners_count }}</td>
                        <td>
                            <button onclick="mostrarSocios({{ $sub->id }})" class="btn">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
         <div class="pagination-container">
            <x-filament::pagination :paginator="$subActivities" class="index mt-4" />
         </div>
    </div>
</x-filament-panels::page>