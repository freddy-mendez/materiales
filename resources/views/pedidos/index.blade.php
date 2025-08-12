<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos</title>
    <style>
        body { font-family: sans-serif; margin: 2rem; }
        .container { max-width: 960px; margin: auto; }
        h1 { text-align: center; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .btn { padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; color: white; }
        .btn-success { background-color: #28a745; }
        .btn-primary { background-color: #007bff; }
        .btn-danger { background-color: #dc3545; }
        .btn-secondary { background-color: #6c757d; }
        .btn-info { background-color: #17a2b8; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 0.75rem; border: 1px solid #dee2e6; text-align: left; }
        th { background-color: #f8f9fa; }
        .badge { display: inline-block; padding: 0.35em 0.65em; font-size: 0.75em; font-weight: 700; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: 0.25rem; color: #fff; }
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: black; }
        .badge-danger { background-color: #dc3545; }
        form { display: inline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gestión de Pedidos</h1>
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
                    <th>ID Pedido</th>
                    <th>Colaborador</th>
                    <th>Fecha</th>
                    <th>Valor Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pedidos as $pedido)
                    <tr>
                        <td>{{ $pedido->id }}</td>
                        <td>{{ $pedido->colaborador->nombre }}</td>
                        <td>{{ $pedido->fecha_pedido }}</td>
                        <td>${{ number_format($pedido->valor_total, 2) }}</td>
                        <td>
                            @if ($pedido->estado == 'pendiente')
                                <span class="badge badge-warning">Pendiente</span>
                            @elseif ($pedido->estado == 'aprobado')
                                <span class="badge badge-success">Aprobado</span>
                            @else
                                <span class="badge badge-danger">Rechazado</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-info">Ver</a>
                            @if ($pedido->estado == 'pendiente')
                                <form action="{{ route('pedidos.approve', $pedido) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Aprobar</button>
                                </form>
                                <form action="{{ route('pedidos.reject', $pedido) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Rechazar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No hay pedidos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        {{ $pedidos->links() }}
    </div>
</body>
</html>