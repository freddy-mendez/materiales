<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar Elementos</title>
    <style>
        body { font-family: sans-serif; margin: 2rem; }
        .form-container { max-width: 600px; margin: auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        input[type="file"], select { width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; color: white; }
        .btn-primary { background-color: #007bff; border: none; cursor: pointer; }
        .btn-secondary { background-color: #6c757d; }
        .error { color: #dc3545; font-size: 0.8rem; margin-top: 0.25rem; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Importar Elementos desde CSV</h1>
        <p><a href="{{ route('elementos.index') }}" class="btn btn-secondary">Volver a Elementos</a></p>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('csv.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="csv_file">Selecciona Archivo CSV</label>
                <input type="file" name="csv_file" id="csv_file" required>
            </div>
            <div class="form-group">
                <label for="categoria_id">Categoría</label>
                <select id="categoria_id" name="categoria_id" required>
                    <option value="">Selecciona una categoría</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Importar</button>
        </form>
    </div>
</body>
</html>