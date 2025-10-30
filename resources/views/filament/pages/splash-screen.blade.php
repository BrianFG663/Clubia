<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clubia</title>
    @vite('resources/css/splash.css')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <div class="contenedor-imagen"><img class="imagen" src="{{asset('images/logos/texturizado.png')}}" alt=""></div>
    
    <h1 class="titulo">Clubia</h1>

    <p class="texto">
        Clubia es una plataforma de gestión inteligente desarrollada para unificar todas las operaciones de un club en un solo sistema. Desde la administración de socios hasta la facturación, pasando por actividades, institutos y personal, todo lo que antes era un desafío, hoy está a tu alcance.
    </p>


    <div class="botones-ingreso">
            <a href="{{ route('login') }}" class="">Información del socio</a>
            <a href="{{ route('admin') }}" class="">Iniciar sesión</a>
    </div>

</body>
</html>
