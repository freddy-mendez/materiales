<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Entrega</title>
    <style>
        body { font-family: sans-serif; margin: 2rem; }
        .form-container { max-width: 600px; margin: auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        input[type="number"], input[type="date"], select { width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; color: white; }
        .btn-primary { background-color: #007bff; border: none; cursor: pointer; }
        .btn-secondary { background-color: #6c757d; }
        .error { color: #dc3545; font-size: 0.8rem; margin-top: 0.25rem; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Registrar Nueva Entrega</h1>
        <form action="{{ route('entregas.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="colaborador_id">Colaborador</label>
                <select id="colaborador_id" name="colaborador_id" required>
                    <option value="">Selecciona un colaborador</option>
                    @foreach ($colaboradores as $colaborador)
                        <option value="{{ $colaborador->id }}" {{ old('colaborador_id') == $colaborador->id ? 'selected' : '' }}>
                            {{ $colaborador->nombre }} (Valor Máximo: ${{ number_format($colaborador->valor_maximo_dinero, 2) }})
                        </option>
                    @endforeach
                </select>
                @error('colaborador_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="elemento_id">Elemento</label>
                <select id="elemento_id" name="elemento_id" required>
                    <option value="">Selecciona un elemento</option>
                    @foreach ($elementos as $elemento)
                        <option value="{{ $elemento->id }}" {{ old('elemento_id') == $elemento->id ? 'selected' : '' }}>
                            {{ $elemento->nombre }} (Precio Unitario: ${{ number_format($elemento->precio_unitario, 2) }})
                        </option>
                    @endforeach
                </select>
                @error('elemento_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="cantidad">Cantidad</label>
                <input type="number" id="cantidad" name="cantidad" min="1" value="{{ old('cantidad') }}" required>
                @error('cantidad')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="fecha_entrega">Fecha de Entrega</label>
                <input type="date" id="fecha_entrega" name="fecha_entrega" value="{{ old('fecha_entrega', date('Y-m-d')) }}" required>
                @error('fecha_entrega')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">Registrar Entrega</button>
            <a href="{{ route('entregas.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>