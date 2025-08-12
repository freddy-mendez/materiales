<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Colaborador</title>
    <style>
        body { font-family: sans-serif; margin: 2rem; }
        .container { max-width: 600px; margin: auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        p { line-height: 1.6; }
        strong { font-weight: bold; }
        .btn-secondary { background-color: #6c757d; border: none; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalles del Colaborador</h1>
        <p><strong>ID:</strong> {{ $colaborador->id ?? 'N/A' }}</p>
        <p><strong>Nombre:</strong> {{ $colaborador->nombre ?? 'N/A' }}</p>
        <p><strong>Identificación:</strong> {{ $colaborador->numero_identificacion ?? 'N/A' }}</p>
        <p><strong>Valor Máximo:</strong> ${{ number_format($colaborador->valor_maximo_dinero ?? 0, 2) }}</p>
        @if ($colaborador->categoria)
            <p><strong>Categoría:</strong> {{ $colaborador->categoria->nombre }}</p>
        @endif
        @if ($colaborador->created_at)
            <p><strong>Creado el:</strong> {{ $colaborador->created_at->format('d/m/Y H:i:s') }}</p>
        @endif
        @if ($colaborador->updated_at)
            <p><strong>Última Actualización:</strong> {{ $colaborador->updated_at->format('d/m/Y H:i:s') }}</p>
        @endif
        <a href="{{ route('colaboradores.index') }}" class="btn-secondary">Volver al Listado</a>
    </div>
</body>
</html>