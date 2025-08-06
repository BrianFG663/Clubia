<x-filament-panels::page>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@vite('resources/css/inscribirSocioActividad.css')
@vite('resources/css/sweet-alert.css')
@vite('resources/js/inscribirSocioActividad.js')

    <form class="contenedor-general" id="formulario" method="POST" action="{{route('registrar.inscripcion')}}">
        @csrf
        <div class="actividad">
            <label for="actividad">Selccione una actividad</label>
            <select name="actividad" id="actividad">
                @foreach ($actividades as $actividad)
                    <option value="{{false}}" disabled selected hidden>--Seleccione actividad--</option>
                    <option value="{{$actividad->id}}">{{$actividad->nombre}}</option>
                @endforeach
            </select>
        </div>
        <div id="sub-actividad-contenedor" class="sub-actividad">
            <label for="actividad">Selccione una sub-actividad</label>
            <select name="subActividad" id="sub-actividad">
                <option value="{{false}}" disabled selected hidden>--Seleccione una sub-actividad--</option>
                <option value="{{false}}">Seleccione actividad primero</option>
            </select>
        </div>
        <div id="socio" class="socios">
            <label for="dni">Ingrese numero de documento del socio a inscribir</label>
            <input type="number" name="dni" id="dni" class="dni" placeholder="--Ingrese numero de documento--">
        </div>

        <input type="button" value="Inscribir socio" class="btn-inscribir" id="btn-inscribir" onclick="inscribirSocio()">
    </form>
</x-filament-panels::page>
