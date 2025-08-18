<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/sweet-alert.css')
    @vite('resources/js/facturas.js')
    @vite('resources/css/facturas.css')

    <div class="contenedor">
        <h2>Facturacion general:</h2>
        <div class="ano-mes">
            <div class="mes">
                <label for="mes">Mes de la facturacion:</label>
                <select name="mes" id="mes"></select>
            </div>

            <div class="ano">
                <label for="ano">Año de la facturacion:</label>
                <select id="ano"></select>
            </div>

        </div>
        <div class="institucion">
            <label for="institutionSelect">Institucion de la facturacion:</label>
            <select id="institutionSelect">
                <option value="{{ false }}" disabled selected hidden>--Seleccione una institucion--</option>
                @foreach ($instituciones as $institucion)
                    <option value="{{ $institucion->id }}">{{ $institucion->nombre }}</option>
                @endforeach
            </select>
        </div>


        <button id="facturarBtn">Comenzar facturacion</button>

    </div>

    <div class="factura-individual">
        <h2>Facturacion individual:</h2>
        <div class="formulario">
            <label class="label-dni" for="dni">Ingresar numero de documento</label>
            <input type="number" id="dni" name="dni" class="dni">
            <input type="button" value="Buscar socio" class="btn-busca-socio" onclick="buscarSocio()">
        </div>
    </div>
    <div class="overlay" id="overlay">
        <div class="resultado-factura-individual" id="resultado-factura-individual">
            <div class="titulo-individual" id="titulo-individual">
                <!-- js -->
            </div>
            <div class="contenedor-individual">
                <div class="inputs-individual">
                    <div class="chechboxs-sub-actividad" id="chechboxs-sub-actividad">
                        <h2 class="titulo-sub-actividad">Seleccione Sub-actividades que desea facturar</h2>
                        <div class="div-checkbox-sub-actividad" id="div-checkbox-sub-actividad">
                            <!-- js -->
                        </div>
                    </div>
                    <div class="chechboxs-socio" id="chechboxs-socio">
                        <h2 class="titulo-socio">Seleccione cuota de socio que desea dacturar</h2>
                        <div class="div-checkbox-tipo-socio" id="div-checkbox-tipo-socio">
                            <!-- js -->
                        </div>
                    </div>
                </div>
                <div class="informacion-individual">
                    <div class="selects-fecha">
                        <h2 class="titulo-fechas">Seleccione fecha a facturar</h2>
                        <label for="mes-individual">Mes de la facturacion:</label>
                        <select id="mes-individual"></select>
                        <label for="ano-individual">Año de la facturacion:</label>
                        <select id="ano-individual"></select>
                    </div>
                    <div class="informacion-socio">
                        <div class="tabla" id="tabla">
                            <table>
                                <thead>
                                    <tr>
                                        <th>grupo familiar</th>
                                    </tr>
                                </thead>
                                <tbody id="body">
                                    <!-- js -->
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <button class="btn-facturacion-individual" onclick="enviarSeleccion()">Comenzar facturacion</button>
        </div>
    </div>


</x-filament-panels::page>
