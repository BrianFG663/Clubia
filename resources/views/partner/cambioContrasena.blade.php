
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/CambioContrasena.css'])
</head>

<body>
    <div class="contenedor">
        <div class="contenedor-titulos">
            <h2>Bienvenido {{Auth::guard('partner')->user()->nombre}} {{Auth::guard('partner')->user()->apellido}}</h2>
            <h3>Por seguridad, es necesario que actualices tu contraseña antes de continuar.</h3>
        </div>
        
        <form method="POST" class="formulario" action="{{ route('partner.contrasena.cambiada') }}">
            @csrf
            <label>Nueva contraseña</label>
            <input type="password" name="contrasena" class="contrasena @error('contrasena') input-error @enderror" required>
            @error('contrasena')
                <div class="errores">{{ $message }}</div>
            @enderror
            <label>Confirmar contraseña</label>
            <input type="password" name="contrasenaConfirmar" class="contrasena @error('contrasena') input-error @enderror" required>
            @error('contrasena')
                <div class="errores">{{ $message }}</div>
            @enderror
            <button type="submit" class="boton">Confirmar</button>
        </form>
    </div>
    
</body>
</html>