<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clubia</title>
    @vite('resources/css/splash.css')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">


</head>
<body>
    @php
            $ruta = 'imagenes/logo.png';
            $logo = Illuminate\Support\Facades\Storage::disk('public')->exists($ruta)
                ? asset("storage/$ruta") . '?v=' . filemtime(public_path("storage/$ruta"))
                : null;
        @endphp

        @if ($logo)
             <div class="contenedor-imagen"><img class="imagen" src="{{$logo}}" alt=""></div>
        @endif

    
    <h1 class="titulo">{{config('app.name')}}</h1>

    <p class="texto">
        Clubia es una plataforma de gestión inteligente desarrollada para unificar todas las operaciones de un club en un solo sistema. Desde la administración de socios hasta la facturación, pasando por actividades, institutos y personal, todo lo que antes era un desafío, hoy está a tu alcance.
    </p>


    <div class="botones-ingreso">
            <a href="{{ route('login') }}" class="">Ingresar como Socio</a>
            <a href="{{ url('/admin') }}" class="">Ingresar como Empleado</a>
    </div>

    <div class="info">
        <span><i class="fa-solid fa-phone"></i>  Teléfono: (+54) 3446-415320</span>
        <span><i class="fa-solid fa-envelope"></i>  E-mail: clubia@gmail.com</span>
        <span><i class="fa-solid fa-location-dot"></i>  Dirección: Santa Fé 74</span>
    </div>

</body>
</html>
