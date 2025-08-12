<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Colaboradores</title>
    <style>
        body { font-family: sans-serif; margin: 2rem; }
        .container { max-width: 960px; margin: auto; }
        h1 { text-align: center; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .btn { padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; color: white; }
        .btn-success { background-color: #28a745; }
        .btn-primary { background-color: #007bff; }
        .btn-warning { background-color: #ffc107; color: black; }
        .btn-danger { background-color: #dc3545; }
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
            <h1>Gestión de Colaboradores</h1>
            <a href="{{ route('colaboradores.create') }}" class="btn btn-success">Crear Nuevo Colaborador</a>
        </div>
        <p><a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver al Dashboard</a></p>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Identificación</th>
                    <th>Valor Máximo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($colaboradores as $colaborador)
                    <tr>
                        <td>{{ $colaborador->id }}</td>
                        <td>{{ $colaborador->nombre }}</td>
                        <td>{{ $colaborador->numero_identificacion }}</td>
                        <td>${{ number_format($colaborador->valor_maximo_dinero, 2) }}</td>
                        <td>
                            <a href="{{ route('colaboradores.show', $colaborador) }}" class="btn btn-primary">Ver</a>
                            <a href="{{ route('colaboradores.edit', $colaborador) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('colaboradores.destroy', $colaborador) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar a este colaborador?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No hay colaboradores registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        {{ $colaboradores->links('vendor.pagination.custom-pagination') }}
        
    </div>
</body>
</html>