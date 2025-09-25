<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/sweet-alert.css')
    @vite('resources/css/parametros.css')
    @vite('resources/js/parametros.js')

    <div class="contenedor-general" id="contenedor-general">
        <div class="contenedor">
            <h1 class="titulo">Umbral máximo de cuotas sociales impagas</h1>

            <div class="descripcion">
                Define el umbral máximo de cuotas sociales impagas que puede tener un socio antes de que se lo considere inactivo. Si la cantidad de facturas sin pagar supera este valor, el sistema cambiará automáticamente el estado del socio a inactivo.            
            </div>

            <select name="institucion" id="institucionSocial">
                @foreach ($instituciones as $institucion)
                    <option value="{{ false }}" disabled selected hidden>--Seleccione una institucion--</option>
                    <option value="{{ $institucion->id }}">{{ $institucion->nombre }}</option>
                @endforeach
            </select>

            <div class="parametros">
                <label for="facturasSocial">Umbral de facturas impagas en cuotas sociales</label>
                <select name="facturasSocial" id="facturasSocial" disabled>
                    <option value="{{null}}" disabled selected hidden>--Seleccione umbral--</option>    
                </select>
            </div>
            <button onclick="parametroCuota()" class="btn">Cambiar parametro</button>
        </div>

        <div class="contenedor">
            <h1 class="titulo">Umbral máximo de cuotas de subactividad impagas</h1>

            <div class="descripcion">
                Define el umbral máximo de cuotas de subactividad impagas que puede tener un socio antes de que se lo desinscriba de la misma. Si la cantidad de facturas sin pagar supera este valor, el sistema lo dará de baja únicamente en esa subactividad.            
            </div>

            <select name="institucion" id="institucionActividad">
                @foreach ($instituciones as $institucion)
                    <option value="{{ false }}" disabled selected hidden>--Seleccione una institucion--</option>
                    <option value="{{ $institucion->id }}">{{ $institucion->nombre }}</option>
                @endforeach
            </select>

            <div class="parametros">
                <label for="facturasActividad">Umbral de facturas impagas en subactividades</label>
                <select name="facturasActividad" id="facturasActividad" disabled>
                    <option value="{{null}}" disabled selected hidden>--Seleccione umbral--</option>
                </select>
            </div>
            <button onclick="parametroSubActividad()" class="btn">Cambiar parametro</button>
        </div>
    </div>

    
</x-filament-panels::page>
