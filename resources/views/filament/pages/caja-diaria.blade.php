<x-filament-panels::page>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@vite('resources/css/sweet-alert.css')
@vite('resources/css/caja-diaria.css')
@vite('resources/js/cajaDiaria.js')


    <div class="contenedor-general" id="contenedor-general">
        <div class="div-select">
            <label for="institucion">Seleccione una institución para ver su caja</label>
            <select name="institucion" id="institucion">
                @foreach ($instituciones as $institucion)
                    <option value="{{ false }}" disabled selected hidden>--Seleccione una institución--</option>
                    <option value="{{$institucion->id}}">{{$institucion->nombre}}</option>
                @endforeach
            </select>
        </div>
        <div class="carrito-vacio" id="carrito-vacio"><img src="{{asset('/images/html/caja-vacia.png')}}" class="img-vacio"><span>No se ha selccionado una institucion</span></div>
    </div>

    <div id="overlay" class="modal-overlay hidden"></div>
    <div class="agregar-movimiento" id="agregar-movimiento">
        <button id="cerrar-alerta"><i class="fa-solid fa-xmark" ></i></button>
        <h2>Agregar un movimiento a caja</h2>
        <label for="descripcion">Ingrese breve descripción del movimiento</label>
        <input type="text" id="descripcion">
        <label for="precio">Ingrese monto del movimiento</label>
        <input type="number" id="total">
        <label for="tipo">Seleccione tipo de movimiento</label>
        <select name="tipo" id="tipo">
            <option value="{{ false }}" disabled selected hidden>--Seleccione un tipo de movimiento--</option>
            <option value="salida">Salida de dinero</option>
            <option value="entrada">Entrada de dinero</option>
        </select>
        <button class="btn-subir" onclick="agregarMovimiento()">Registrar movimiento</button>
    </div>

    <button class="btn-registrar" id="btn-registrar">Ingresar nuevo movimiento</button>

</x-filament-panels::page>
