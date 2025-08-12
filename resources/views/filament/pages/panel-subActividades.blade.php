<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/panel-subactividades.css')
    @vite('resources/css/sweet-alert.css')
    @vite('resources/js/panel-subActividades.js')


    <div class="contenedor">
        <div id="overlay" class="hidden"></div>
        <div class="detalle-socios hidden" id="detalle-socios">
            <button id="cerrar-detalle"><i class="fa-solid fa-xmark"></i></button>
            <div id="contenedor-informacion"></div>
        </div>

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
            <tbody>
                @foreach ($subActivities as $sub)

                    <tr>
                        <td>{{ $sub->activity->nombre }}</td>
                        <td>{{ $sub->nombre }}</td>
                        <td>{{ $sub->monto  }}</td>
                        <td >{{ $sub->partners_count }}</td>
                        <td>
                            <button onclick="mostrarSocios({{ $sub->id }})" class="btn-ver">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
        <x-filament::pagination :paginator="$subActivities" class="index mt-4" />

    </div>
</x-filament-panels::page>