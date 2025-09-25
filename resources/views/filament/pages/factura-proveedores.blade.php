<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/facturaProveedores.css')
    @vite('resources/css/sweet-alert.css')
    @vite('resources/js/facturaProveedores.js')
    
    <div class="contenedor-general">
        <div class="contenedor-selects">
            <div class="select-js" id="select-js">
                <label for="input-vista" class="label-vista">Seleccione proveedor</label>
                <select name="select-js" id="proveedor">
                    @foreach ($proveedores as $proveedor)
                        <option value="{{ false }}" disabled selected hidden>--Seleccione un proveedor--</option>
                        <option value="{{$proveedor->id}}">{{$proveedor->nombre}}</option>
                    @endforeach
                </select>                
            </div>
        </div>

        <div class="facturas" id="facturas">
            <div class="facturas-pagas" id="facturas-pagas">
                <div class="carrito-vacio"><img src="{{asset('/images/html/caja-vacia.png')}}" class="img-vacio"><span>No se ha selccionado proveedor</span></div>
            </div>
            <div class="facturas-no-pagas" id="facturas-no-pagas">
                <div class="carrito-vacio"><img src="{{asset('/images/html/caja-vacia.png')}}" class="img-vacio"><span>No se ha selccionado proveedor</span></div>
            </div>
        </div>

    </div>
</x-filament-panels::page>
