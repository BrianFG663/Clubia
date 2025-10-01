<x-filament-panels::page>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/registroCajasDiarias.css')
    @vite('resources/css/sweet-alert.css')
    @vite('resources/js/registroCajasDiarias.js')
    <div class="contenedor">

        <div class="filtro-container">
            <div class="input-icon-wrapper">
                <input type="text" id="filtroInstitucion" placeholder="Buscar Institucion/fecha" aria-label="Filtro por nombre" class="filtro-input"/>
                <i class="fa-solid fa-magnifying-glass icon"></i>
            </div>
        </div>

        <div id="overlay" class="modal-overlay hidden"></div>
        <div class="movimientos" id="movimientos">
            <button id="cerrar-alerta"><i class="fa-solid fa-xmark" ></i></button>
            <div id="contenedor-informacion">
                
            </div>
        </div>
        <div class="tabla-container">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Institución</th>
                        <th>Fecha de registro</th>
                        <th>Balance de caja</th>
                        <th>Movimientos</th>
                        <th class="botones" colspan="2">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaCuerpo">
                    @foreach ($CashRecords as $CashRecord)
                        <tr>
                            <td class="nombre">{{ $CashRecord->institution->nombre}}</td>
                            <td class="fecha">{{ $CashRecord->fecha }}</td>
                            <td>${{ number_format($CashRecord->total, 2) }}</td>
                            <td>{{ count($CashRecord->cashRecordsDetails) }}</td>
                            <td class="btn"><button id="btn-ver" onclick="movimientos({{$CashRecord->id,}})"><i class="fa-regular fa-eye"></i></button></td>
                            <td class="btn"><button id="btn-eliminar" onclick="eliminarRegistro({{$CashRecord->id}})"><i class="far fa-trash-alt"></i></button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-container">
            <x-filament::pagination :paginator="$CashRecords" class="index" />
        </div>
    </div>
</x-filament-panels::page>
