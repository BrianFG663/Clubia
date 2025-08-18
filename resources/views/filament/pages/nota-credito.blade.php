<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/notaCredito.css')
    @vite('resources/css/sweet-alert.css')
    @vite('resources/js/notaCredito.js')


    <div class="contenedor-general">
        <div class="contenedor-selects">
            <div class="select-actividad">
                <label for="actividad">Seleccione actividad para la nota de credito</label>
                <select id="actividad">
                    <option value="{{ false }}" disabled selected hidden>--Seleccione una actividad--</option>
                    <option value="1">Socio</option>
                    <option value="2">Proveedor</option>
                    <option value="3">Ventas</option>
                </select>
            </div>
            <div class="select-js" id="select-js">
                <label for="input-vista" class="label-vista">--Primero debe seleccionar una actividad--</label>
                <input type="number" id="input-vista" class="input-vista" disabled>                
            </div>
        </div>

        <div class="facturas" id="facturas">
            <div class="facturas-pagas" id="facturas-pagas">
                <div class="carrito-vacio"><img src="{{asset('/images/html/caja-vacia.png')}}" class="img-vacio"><span>No se ha selccionado proveedor/socio/ventas</span></div>
            </div>
            <div class="facturas-no-pagas" id="facturas-no-pagas">
                <div class="carrito-vacio"><img src="{{asset('/images/html/caja-vacia.png')}}" class="img-vacio"><span>No se ha selccionado proveedor/socio/ventas</span></div>
            </div>
        </div>

    </div>
</x-filament-panels::page>
