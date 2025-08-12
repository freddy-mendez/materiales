<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Colaborador</title>
    <style>
        body { font-family: sans-serif; margin: 2rem; }
        .form-container { max-width: 600px; margin: auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        input[type="text"], input[type="number"], select { width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; color: white; }
        .btn-primary { background-color: #007bff; border: none; cursor: pointer; }
        .btn-secondary { background-color: #6c757d; }
        .error { color: #dc3545; font-size: 0.8rem; margin-top: 0.25rem; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Crear Nuevo Colaborador</h1>
        <p><a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver al Dashboard</a></p>

        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('colaboradores.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                @error('nombre')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="numero_identificacion">Número de Identificación</label>
                <input type="text" id="numero_identificacion" name="numero_identificacion" value="{{ old('numero_identificacion') }}" required>
                @error('numero_identificacion')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="valor_maximo_dinero">Valor Máximo en Dinero</label>
                <input type="number" id="valor_maximo_dinero" name="valor_maximo_dinero" step="0.01" value="{{ old('valor_maximo_dinero') }}" required>
                @error('valor_maximo_dinero')
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
            
            <button type="submit" class="btn btn-primary">Guardar Colaborador</button>
            <a href="{{ route('colaboradores.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>