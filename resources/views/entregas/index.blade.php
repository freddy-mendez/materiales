<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Entregas</title>
    <style>
        body { font-family: sans-serif; margin: 2rem; }
        .container { max-width: 960px; margin: auto; }
        h1 { text-align: center; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .btn { padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; color: white; }
        .btn-primary { background-color: #007bff; }
        .btn-secondary { background-color: #6c757d; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 0.75rem; border: 1px solid #dee2e6; text-align: left; }
        th { background-color: #f8f9fa; }
        form { display: inline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registro de Entregas</h1>
            <a href="{{ route('entregas.create') }}" class="btn btn-primary">Registrar Nueva Entrega</a>
        </div>
        <p><a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver al Dashboard</a></p>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Colaborador</th>
                    <th>Elemento</th>
                    <th>Cantidad</th>
                    <th>Valor Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($entregas as $entrega)
                    <tr>
                        <td>{{ $entrega->fecha_entrega }}</td>
                        <td>{{ $entrega->colaborador->nombre }}</td>
                        <td>{{ $entrega->elemento->nombre }}</td>
                        <td>{{ $entrega->cantidad }}</td>
                        <td>${{ number_format($entrega->valor_total_entregado, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No hay entregas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        {{ $entregas->links() }}
    </div>
</body>
</html>