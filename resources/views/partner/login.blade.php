<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/loginSocio.css'])
</head>

<body>
    <div class="contenedor">
        <div class="imagen-titulo">
            <img src="{{asset('images/logos/texturizado.png')}}" class="logo">
            <div class="titulo">Iniciar sesion</div>
        </div>
        <form method="POST" action="{{ route('partner.login') }}">
            @csrf
            <div class="input">
                <label for="email" class="m-label">Correo electrónico</label>
                <input type="email" name="email" class="mail @error('email') input-error @enderror"
                    value="{{ old('email') }}" required />
            </div>
            @error('email')
                <div class="errores">{{ $message }}</div>
            @enderror

            <div class="input">
                <label for="password" class="c-label">Contraseña</label>
                <input type="password" name="password" class="password @error('password') input-error @enderror"
                    required />
            </div>

            @error('password')
                <div class="errores">{{ $message }}</div>
            @enderror

            <button type="submit">Acceder</button>
        </form>
    </div>
</body>

</html>
