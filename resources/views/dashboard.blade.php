<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Bienvenido, {{ auth()->user()->nombre_usuario }}!</h1>
    <p>Has iniciado sesión correctamente.</p>

    <form action="{{ route('logout') }}" method="POST" style="margin-top: 2rem;">
        @csrf
        <button type="submit">Cerrar Sesión</button>
    </form>
</body>
</html>