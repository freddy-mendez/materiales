<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f0f0f0; }
        .form-container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        input[type="text"], input[type="password"] { width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #007bff; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
        button:hover { background-color: #0056b3; }
        .error { color: #d9534f; font-size: 0.8rem; margin-top: 0.25rem; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Iniciar Sesión</h2>
        <form action="{{ route('login') }}" method="POST">
            @csrf

            @if ($errors->any() && !old('nombre_usuario'))
                <div class="error" style="text-align: center; margin-bottom: 1rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" value="{{ old('nombre_usuario') }}" required>
                @error('nombre_usuario')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Iniciar Sesión</button>
        </form>
        <p style="text-align:center; margin-top: 1rem;"><a href="{{ route('register') }}">¿No tienes una cuenta? Regístrate</a></p>
    </div>
</body>
</html>