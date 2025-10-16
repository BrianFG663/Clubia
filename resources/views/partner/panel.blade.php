<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/panelSocios.css', 'resources/js/PanelSocios.js'])
</head>

<body>
    <header>
        <img src="{{ asset('images/logos/texturizado.png') }}" class="logo">
        <h1 class="titulo">CLUBIA</h1>
        <form method="POST" action="{{ route('partner.logout') }}">
            @csrf
            <button type="submit" class="form-logout"><i class="fa-solid fa-sign-out-alt"
                title="Cerrar sesion"></i></button>
        </form>
    </header>

    <div class="facturas-estado">
        <div class="estado">
            <div class="nombre">{{ $partner->nombre }} {{ $partner->apellido }}</div>
            @if ($partner->state->nombre == 'Activo')
                <h2 class="estado-cuenta"><span class="punto-verde"></span>{{ $partner->state->nombre }}</h2>
            @else
                <h2 class="estado-cuenta"><span class="punto-rojo"></span>{{ $partner->state->nombre }}</h2>
            @endif
        </div>
        <div class="facturas">
            <div class="inpagas">
                <div class="titulo-facturas">Facturas Pendientes</div>
                <div class="cuerpo-facturas">
                    <div class="cantidad">
                        {{ count($facturasInpagas) }}
                    </div>
                    <i class="fa-regular fa-eye" title="Ver facturas"
                        onclick="facturasInpagas({{ auth('partner')->user()->id }})"></i>
                </div>
            </div>
            <div class="pagas">
                <div class="titulo-facturas">Tus Facturas Pagadas</div>
                <div class="cuerpo-facturas">
                    <div class="cantidad">
                        {{ count($facturasPagas) }}
                    </div>
                    <i class="fa-regular fa-eye" title="Ver facturas" onclick="facturasPagas({{ auth('partner')->user()->id }})"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="detalles">
        <div class="informacion">
            <table class="tabla-informacion">
                <thead>
                    <tr>
                        <th class="th-inf" colspan="2">Informacion personal:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="uno">Dni:</td>
                        <td class="dos">{{ $partner->dni }}</td>
                    </tr>
                    <tr>
                        <td class="uno">Email:</td>
                        <td class="dos">{{ $partner->email }}</td>
                    </tr>
                    <tr>
                        <td class="uno">Fecha de nacimiento:</td>
                        <td class="dos">{{ $partner->fecha_nacimiento }}</td>
                    </tr>
                    <tr>
                        <td class="uno">Direccion:</td>
                        <td class="dos">{{ $partner->direccion }}</td>
                    </tr>
                    <tr>
                        <td class="uno">Ciudad:</td>
                        <td class="dos">{{ $partner->ciudad }}</td>
                    </tr>
                    <tr>
                        <td class="uno">Telefono:</td>
                        <td class="dos">{{ $partner->telefono }}</td>
                    </tr>

                </tbody>
            </table>
        </div>
        <div class="actividades">
            <div class="tipo">
                <table class="tabla-tipo">
                    <thead>
                        <tr>
                            <th class="th-tipo">Tipo/s de socio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($partner->memberTypes as $tipo)
                            <tr>
                                <td class="td-titulo">{{ $tipo->nombre }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="sub-actividades">
                <table class="tabla-sub">
                    <thead>
                        <tr>
                            <th class="th-sub">Actividades:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($partner->subActivities->isNotEmpty())
                            @foreach ($partner->subActivities as $sub)
                                <tr>
                                    <td class="td-titulo">{{ $sub->nombre }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="td-titulo">No hay actividades inscriptas.</td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
        <div class="grupo">
            <h1 class="titulo-grupo">Grupo familiar</h1>
            @if ($partner->jefe_grupo == 1)
                <div class="familiar">
                    <table class="tabla-informacion">
                        <thead>
                            <tr>
                                <th class="th-inf" colspan="2">{{ $partner->nombre }} {{ $partner->apellido }}
                                    (Responsable):</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="uno">Dni:</td>
                                <td class="dos">{{ $partner->dni }}</td>
                            </tr>
                            <tr>
                                <td class="uno">Fecha de nacimiento:</td>
                                <td class="dos">{{ $partner->fecha_nacimiento }}</td>
                            </tr>
                            <tr>
                                <td class="uno">Direccion:</td>
                                <td class="dos">{{ $partner->direccion }}</td>
                            </tr>
                            <tr>
                                <td class="uno">Telefono:</td>
                                <td class="dos">{{ $partner->telefono }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                @foreach ($partner->familyMembers as $familiar)
                    <div class="familiar">
                        <table class="tabla-informacion">
                            <thead>
                                <tr>
                                    <th class="th-inf" colspan="2">{{ $familiar->nombre }}
                                        {{ $familiar->apellido }}:</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="uno">Dni:</td>
                                    <td class="dos">{{ $familiar->dni }}</td>
                                </tr>
                                <tr>
                                    <td class="uno">Fecha de nacimiento:</td>
                                    <td class="dos">{{ $familiar->fecha_nacimiento }}</td>
                                </tr>
                                <tr>
                                    <td class="uno">Direccion:</td>
                                    <td class="dos">{{ $familiar->direccion }}</td>
                                </tr>
                                <tr>
                                    <td class="uno">Telefono:</td>
                                    <td class="dos">{{ $familiar->telefono }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                @endforeach
            @endif

            @if ($partner->responsable_id !== null)
                <div class="familiar">
                    <table class="tabla-informacion">
                        <thead>
                            <tr>
                                <th class="th-inf" colspan="2">{{ $partner->responsable->nombre }}
                                    {{ $partner->responsable->apellido }} (Responsable):</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="uno">Dni:</td>
                                <td class="dos">{{ $partner->responsable->dni }}</td>
                            </tr>
                            <tr>
                                <td class="uno">Fecha de nacimiento:</td>
                                <td class="dos">{{ $partner->responsable->fecha_nacimiento }}</td>
                            </tr>
                            <tr>
                                <td class="uno">Direccion:</td>
                                <td class="dos">{{ $partner->responsable->direccion }}</td>
                            </tr>
                            <tr>
                                <td class="uno">Telefono:</td>
                                <td class="dos">{{ $partner->responsable->telefono }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                @foreach ($partner->responsable->familyMembers as $familiar)
                    <div class="familiar">
                        <table class="tabla-informacion">
                            <thead>
                                <tr>
                                    <th class="th-inf" colspan="2">{{ $familiar->nombre }}
                                        {{ $familiar->apellido }}:</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="uno">Dni:</td>
                                    <td class="dos">{{ $familiar->dni }}</td>
                                </tr>
                                <tr>
                                    <td class="uno">Fecha de nacimiento:</td>
                                    <td class="dos">{{ $familiar->fecha_nacimiento }}</td>
                                </tr>
                                <tr>
                                    <td class="uno">Direccion:</td>
                                    <td class="dos">{{ $familiar->direccion }}</td>
                                </tr>
                                <tr>
                                    <td class="uno">Telefono:</td>
                                    <td class="dos">{{ $familiar->telefono }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                @endforeach
            @endif

            @if ($partner->responsable_id == null && $partner->jefe_grupo == 0)
                <div class="mensaje-vacio">
                    <img src="{{ asset('images/html/caja-vacia.png') }}" class="imagen-vacio"><span
                        class="texto-vacio">Usted no se encuentra dentro de un grupo familiar.</span>
                </div>
            @endif
        </div>
    </div>

    <div id="overlay" class="modal-overlay hidden"></div>
    <div class="facturas-mostrar" id="facturas-mostrar">
        <button id="cerrar-alerta"><i class="fa-solid fa-xmark"></i></button>
        <div id="contenedor-informacion">
        </div>
    </div>
</body>

</html>
