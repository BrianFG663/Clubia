<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/grupo-familiar.css')
    @vite('resources/css/sweet-alert.css')
    @vite('resources/js/grupo-familiar.js')
    <div class="contenedor">

        <div class="filtro-container">
            <div class="input-icon-wrapper">
                <input type="text" id="filtroNombre" placeholder="Buscar Nombre/DNI" aria-label="Filtro por nombre" class="filtro-input"/>
                <i class="fa-solid fa-magnifying-glass icon"></i>
            </div>
            <div>
                <button class="btn-buscar" onclick="buscarGrupoFamiliar()">Buscar</button>
            </div>
        </div>


        <div id="overlay" class="modal-overlay hidden"></div>
        <div class="familiares" id="familiares">
            <button id="cerrar-alerta"><i class="fa-solid fa-xmark" ></i></button>
            <div id="contenedor-informacion">
                
            </div>
        </div>

        <div class="tabla-container">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Familiar a cargo</th>
                        <th>DNI</th>
                        <th>E-mail</th>
                        <th>Telefono</th>
                        <th class="botones" colspan="2">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaCuerpo">
                    @foreach ($jefes as $jefe)
                        <tr>
                            <td class="nombre">{{ $jefe->nombre }} {{ $jefe->apellido }}</td>
                            <td>{{ $jefe->dni }}</td>
                            <td class="email">{{ $jefe->email }}</td>
                            <td>{{ $jefe->telefono }}</td>
                            <td class="btn"><button id="btn-ver" onclick="detalleFamilia({{$jefe->id}})"><i class="fa-solid fa-eye"></i></button></td>
                            <td class="btn"><button id="btn-agregar" onclick="agregarIntegrante({{$jefe->id}})"><i class="fa-solid fa-user-plus"></i></button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-container">
            <x-filament::pagination :paginator="$jefes" class="index" />
        </div>
    </div>
</x-filament-panels::page>
