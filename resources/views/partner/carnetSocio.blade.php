<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/carnetSocio.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

@php
    $partnerId = Auth::guard('partner')->id();
    $partner = App\Models\Partner::find($partnerId);
@endphp
<body>
    <header>
        <img src="{{ asset('images/logos/texturizado.png') }}" class="logo">
        <h1 class="titulo">CLUBIA</h1>
        <form action="{{ route('partner.panel') }}" method="get" class="form-socio">
            @csrf
            <button class="boton-carnet" title="Mostrar carnet"><i class="fa-solid fa-house"></i></i></button>
        </form>
        <form method="POST" action="{{ route('partner.logout') }}" class="form-logout">
            @csrf
            <button type="submit"><i class="fa-solid fa-sign-out-alt" title="Cerrar sesion"></i></button>
        </form>
    </header>
    <div class="carnet">
        <div class="contenedor-imagen">
        @if (!$partner->getFirstMedia('profile'))
            <img class="imagen-pendiente" src="{{ asset('images/html/cuenta.png') }}">
        @endif
        @if ($partner->getFirstMedia('profile')?->checked === 'aprobado')
            <img class="imagen-carnet" src="{{ asset('storage/' . $partner->getFirstMedia('profile')->getPathRelativeToRoot('profile')) }}">
        @elseif ($partner->getFirstMedia('profile')?->checked === 'pendiente')
            <img class="imagen-pendiente" src="{{ asset('images/alertas/lupa.png') }}">
            <span class="mesanje-foto">Foto pendiente de revision.</span>
        @elseif ($partner->getFirstMedia('profile')?->checked === 'rechazado')
            <img class="imagen-carnet" src="{{ asset('images/html/cuenta.png') }}">
            <span class="mesanje-foto">Foto rechazada, por favor prueba con otra.</span>
        @endif

            <form class="formulario" method="POST" action="{{ route('partner.cambio.imagen') }}" enctype="multipart/form-data">
                @csrf
                <label for="photo">Cambiar foto de perfil:</label>
                <input class="input" type="file" name="photo" accept="image/*" required>
                <button class="boton" type="submit">Cambiar foto de perfil</button>
            </form>
        </div>
        <div class="contenedor-inf">
            <div class="titulo-inf">CARNET DE SOCIO {{strtoupper(config('app.name'))}}</div>
            <table class="tabla">
                <tbody>
                    <tr>
                        <td><span>Nombre:</span> {{$partner->nombre}} {{$partner->apellido}}</td>
                        <td><span>Direccion:</span> {{$partner->direccion}}</td>
                    </tr>
                    <tr>
                        <td><span>DNI:</span> {{$partner->dni}}</td>
                        <td><span>Nº socio:</span> #2323232</td>
                    </tr>
                    <tr>
                        <td><span>Ciudad:</span> {{$partner->ciudad}}</td>
                        <td><span>Telefono:</span> {{$partner->telefono}}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><span>correo electronico:</span> {{$partner->email}}</td>
                    </tr>
                </tbody>
            </table>
            <img src="{{ asset('images/logos/texturizado.png') }}"  class="imagen-logo">
        </div>
    </div>
</body>

</html>
