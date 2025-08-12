<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Pedido</title>
    <style>
        body { font-family: sans-serif; margin: 2rem; }
        .form-container { max-width: 800px; margin: auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        input[type="date"], select, .element-row { width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .element-row { display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem; padding: 0.5rem; border: 1px solid #eee; }
        .element-row input, .element-row select { flex-grow: 1; }
        .add-btn { background-color: #28a745; color: white; border: none; cursor: pointer; padding: 0.5rem 1rem; border-radius: 4px; }
        .remove-btn { background-color: #dc3545; color: white; border: none; cursor: pointer; padding: 0.5rem 1rem; border-radius: 4px; }
        .btn { padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; color: white; }
        .btn-primary { background-color: #007bff; border: none; cursor: pointer; }
        .btn-secondary { background-color: #6c757d; }
        .error { color: #dc3545; font-size: 0.8rem; margin-top: 0.25rem; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Crear Nuevo Pedido</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pedidos.store') }}" method="POST">
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
                <label for="fecha_pedido">Fecha del Pedido</label>
                <input type="date" id="fecha_pedido" name="fecha_pedido" value="{{ old('fecha_pedido', date('Y-m-d')) }}" required>
                @error('fecha_pedido')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <h3>Elementos del Pedido</h3>
            <div id="elementos-container">
                <div class="element-row">
                    <select name="elementos[0][elemento_id]" required>
                        <option value="">Selecciona un elemento</option>
                        @foreach ($elementos as $elemento)
                            <option value="{{ $elemento->id }}">
                                {{ $elemento->nombre }} (${{ number_format($elemento->precio_unitario, 2) }})
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="elementos[0][cantidad]" placeholder="Cantidad" min="1" required>
                    <button type="button" class="remove-btn" onclick="this.parentNode.remove()">Eliminar</button>
                </div>
            </div>
            
            <button type="button" class="add-btn" onclick="addElemento()">Añadir Elemento</button>
            <br><br>

            <button type="submit" class="btn btn-primary">Crear Pedido</button>
            <a href="{{ route('pedidos.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script>
        let elementoIndex = 1;
        function addElemento() {
            const container = document.getElementById('elementos-container');
            const newRow = document.createElement('div');
            newRow.classList.add('element-row');
            newRow.innerHTML = `
                <select name="elementos[${elementoIndex}][elemento_id]" required>
                    <option value="">Selecciona un elemento</option>
                    @foreach ($elementos as $elemento)
                        <option value="{{ $elemento->id }}">
                            {{ $elemento->nombre }} (${{ number_format($elemento->precio_unitario, 2) }})
                        </option>
                    @endforeach
                </select>
                <input type="number" name="elementos[${elementoIndex}][cantidad]" placeholder="Cantidad" min="1" required>
                <button type="button" class="remove-btn" onclick="this.parentNode.remove()">Eliminar</button>
            `;
            container.appendChild(newRow);
            elementoIndex++;
        }
    </script>
</body>
</html>