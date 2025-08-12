<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Elementos</title>
    <style>
        body { font-family: sans-serif; margin: 2rem; }
        .container { max-width: 1200px; margin: auto; }
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
        .text-right { text-align: right; }
        .text-center {text-align: center;}
        
        /* Estilos para la paginación */
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin-top: 1rem;
        }
        .pagination li {
            margin: 0 0.25rem;
        }
        .pagination a, .pagination span {
            display: inline-block;
            padding: 0.5rem 0.75rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-decoration: none;
            color: #007bff;
            font-size: 1rem;
        }
        .pagination a:hover {
            background-color: #f0f0f0;
        }
        .pagination .active span {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        /* Estilos para el formulario de filtro */
        .filter-form {
            margin-bottom: 1rem;
            text-align: right;
        }
        .filter-form form > * {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gestión de Elementos</h1>
            <a href="{{ route('elementos.create') }}" class="btn btn-success">Crear Nuevo Elemento</a>
        </div>
        <p><a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver al Dashboard</a></p>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="filter-form">
            <form action="{{ route('elementos.index') }}" method="GET">
                <label for="nombre">Filtrar por Nombre:</label>
                <input type="text" name="nombre" id="nombre" value="{{ request('nombre') }}" placeholder="Nombre del elemento">
                
                <label for="categoria_id">Filtrar por Categoría:</label>
                <select name="categoria_id" id="categoria_id">
                    <option value="">Todas</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Aplicar</button>
                <a href="{{ route('elementos.index') }}" class="btn btn-secondary">Limpiar</a>
            </form>
        </div>

        <table>
            <thead>
                <tr >
                    <th class="text-center">ID</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Precio</th>
                    <th class="text-center">Unidad de Medida</th>
                    <th class="text-center">Categoría</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($elementos as $elemento)
                    <tr>
                        <td>{{ $elemento->id }}</td>
                        <td style="width: 400px;">{{ $elemento->descripcion }}</td>
                        <td class="text-right">${{ number_format($elemento->precio_unitario, 2) }}</td>
                        <td>{{ $elemento->unidad_de_medida ?? 'N/A' }}</td>
                        <td style="width: 200px;">{{ $elemento->categoria->nombre }}</td>
                        <td >
                            <a href="{{ route('elementos.edit', $elemento) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('elementos.destroy', $elemento) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No hay elementos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        {{ $elementos->appends(request()->query())->links('vendor.pagination.custom-pagination') }}
    </div>
</body>
</html>