<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías</title>
    <style>
        body { font-family: sans-serif; margin: 2rem; }
        .container { max-width: 960px; margin: auto; }
        h1 { text-align: center; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .btn { padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; color: white; }
        .btn-success { background-color: #28a745; }
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
            <h1>Gestión de Categorías</h1>
            <a href="{{ route('categorias.create') }}" class="btn btn-success">Crear Nueva Categoría</a>
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
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categorias as $categoria)
                    <tr>
                        <td>{{ $categoria->id }}</td>
                        <td>{{ $categoria->nombre }}</td>
                        <td>
                            <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('categorias.destroy', $categoria) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No hay categorías registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        {{ $categorias->links() }}
    </div>
</body>
</html>