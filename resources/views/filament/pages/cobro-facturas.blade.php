<x-filament-panels::page>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/cobroFacturas.css', 'resources/js/cobroFacturas.js'])

    <div class="contenedor">
        <div class="filtro-container">
            <div class="input-icon-wrapper">
                <input
                type="text"
                id="filtroNombre"
                placeholder="Buscar Nombre/DNI"
                aria-label="Filtro por nombre/DNI"
                class="filtro-input"
                />
                <i class="fa-solid fa-magnifying-glass icon"></i>
                
            </div>
        </div>



        <div class="tabla-container">
            <table class="tabla">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>DNI</th>
            <th>Responsable</th>
            <th>Facturas Impagas</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="tablaCuerpo">
        @foreach($partners as $partner)
            <tr>
                <td>{{ $partner->nombre . ' ' . $partner->apellido }}</td>
                <td>{{ $partner->dni ?? '-' }}</td>

                <td>
                    @if($partner->jefe_grupo)
                        Sí
                    @else
                        No
                    @endif
                </td>
                
          <td>
    @php
        $totalImpagas = $partner->invoices->count();

        if ($partner->jefe_grupo) {
            $totalImpagas += \App\Models\Partner::where('responsable_id', $partner->id)
                ->with('invoices')
                ->get()
                ->sum(fn($dep) => $dep->invoices->count());
        }
    @endphp
    {{ $totalImpagas }}
</td>

                <td>
                    <button class="btn-ver" onclick="verFacturas({{ $partner->id }})">
                        Ver Facturas
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


            <div class="pagination-container">
                <x-filament::pagination :paginator="$partners"/>
            </div>
        </div>

           <div id="detalle-factura" class="detalle-factura">
            <button id="cerrar-factura" class="cerrar-factura" aria-label="Cerrar modal">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div id="contenedor-informacion"></div>
        </div>
        <div id="overlay-factura"></div>

    </div>
</x-filament-panels::page>
