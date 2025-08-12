<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Colaborador</title>
    <style>
        body { font-family: sans-serif; margin: 2rem; }
        .container { max-width: 800px; margin: auto; background-color: #f4f4f4; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1, h2 { text-align: center; color: #333; }
        .info-card { display: flex; justify-content: space-around; margin: 1rem 0; padding: 1rem; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .info-card div { text-align: center; }
        .info-card h3 { margin-bottom: 0.5rem; }
        .info-card p { font-size: 1.5rem; font-weight: bold; }
        .btn { padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none; color: white; background-color: #007bff; display: inline-block; margin-top: 1rem; }
        .logout-form { text-align: right; margin-top: 1rem; }
        .logout-btn { background: none; border: none; color: #dc3545; text-decoration: underline; cursor: pointer; font-size: 1rem; padding: 0; font-weight: bold; }
        .alert-success { background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .alert-danger { background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logout-form">
            <p>Bienvenido, {{ $colaborador->nombre }}!</p>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Cerrar Sesión</button>
            </form>
        </div>
        
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('errors'))
            <div class="alert alert-danger">
                {{ session('errors')->first() }}
            </div>
        @endif

        <div class="info-card">
            <div>
                <h3>Valor Máximo</h3>
                <p>${{ number_format($colaborador->valor_maximo_dinero, 2) }}</p>
            </div>
            <div>
                <h3>Valor Gastado</h3>
                <p>${{ number_format($valor_aprobado, 2) }}</p>
            </div>
            <div>
                <h3>Valor Pendiente</h3>
                <p>${{ number_format($valor_pendiente, 2) }}</p>
            </div>
            <div>
                <h3>Valor Disponible</h3>
                <p>${{ number_format($valor_disponible, 2) }}</p>
            </div>
        </div>

        <hr>

        <h2>Solicitudes</h2>
        <a href="{{ route('colaborador.solicitar') }}" class="btn">Solicitar Elementos</a>
        <a href="{{ route('colaborador.reportes') }}" class="btn btn-secondary">Ver mis Pedidos</a>
    </div>
</body>
</html>