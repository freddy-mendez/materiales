<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Elemento</title>
    <style>
        body { font-family: sans-serif; margin: 2rem; }
        .form-container { max-width: 600px; margin: auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        input[type="text"], input[type="number"], textarea, select { width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; color: white; }
        .btn-primary { background-color: #007bff; border: none; cursor: pointer; }
        .btn-secondary { background-color: #6c757d; }
        .error { color: #dc3545; font-size: 0.8rem; margin-top: 0.25rem; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Crear Nuevo Elemento</h1>
        <p><a href="{{ route('elementos.index') }}" class="btn btn-secondary">Volver al Listado</a></p>
        <form action="{{ route('elementos.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                @error('nombre')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción (opcional)</label>
                <textarea id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="precio_unitario">Precio Unitario</label>
                <input type="number" id="precio_unitario" name="precio_unitario" step="0.01" value="{{ old('precio_unitario') }}" required>
                @error('precio_unitario')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="unidad_de_medida">Unidad de Medida (Ej: cm, ml, unidad)</label>
                <input type="text" id="unidad_de_medida" name="unidad_de_medida" value="{{ old('unidad_de_medida') }}">
                @error('unidad_de_medida')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="categoria_id">Categoría</label>
                <select id="categoria_id" name="categoria_id" required>
                    <option value="">Selecciona una categoría</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Guardar Elemento</button>
            <a href="{{ route('elementos.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>