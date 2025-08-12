<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Pedidos</title>
    <style>
        body { font-family: sans-serif; margin: 2rem; }
        .container { max-width: 1100px; margin: auto; }
        h1 { text-align: center; }
        .filters { margin-bottom: 2rem; }
        .filters label { font-weight: bold; }
        .filters select, .filters input { padding: 0.5rem; border-radius: 4px; border: 1px solid #ccc; margin-right: 1rem; }
        .btn { padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; color: white; border: none; cursor: pointer; }
        .btn-primary { background-color: #007bff; }
        .btn-secondary { background-color: #6c757d; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 0.75rem; border: 1px solid #dee2e6; text-align: left; vertical-align: top; }
        th { background-color: #f8f9fa; }
        .badge { display: inline-block; padding: 0.35em 0.65em; font-size: 0.75em; font-weight: 700; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: 0.25rem; color: #fff; }
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: black; }
        .badge-danger { background-color: #dc3545; }
        .signature-container { margin-top: 4rem; text-align: center; }
        .signature-line { border-top: 1px solid #000; display: inline-block; width: 300px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .ancho-fecha{width: 90px;}
        @media print {
            .filters, .btn, p.no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reportes de Pedidos</h1>
        <p class="no-print"><a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver al Dashboard</a></p>

        @if ($user->rol === 'administrador')
            <h3 class="no-print">Filtros de Reporte</h3>
            <div class="filters no-print">
                <form action="{{ route('reports.index') }}" method="GET">
                    <label for="colaborador_id">Colaborador:</label>
                    <select name="colaborador_id">
                        <option value="">Todos</option>
                        @foreach ($colaboradores as $colaborador)
                            <option value="{{ $colaborador->id }}" {{ request('colaborador_id') == $colaborador->id ? 'selected' : '' }}>
                                {{ $colaborador->nombre }}
                            </option>
                        @endforeach
                    </select>

                    <label for="categoria_id">Categoría:</label>
                    <select name="categoria_id">
                        <option value="">Todas</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>

                    <label for="pedido_id">Número de Pedido:</label>
                    <input type="text" name="pedido_id" value="{{ request('pedido_id') }}">

                    <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">Limpiar Filtros</a>
                </form>
            </div>
            @if ($pedidos->count() === 1)
                <div style="text-align: right; margin-bottom: 1rem;" class="no-print">
                    <button class="btn btn-success" onclick="window.print()">Imprimir en PDF</button>
                </div>
            @endif
        @endif

       
        @if (request('pedido_id'))
            @foreach ($pedidos as $pedido)
                @if ($pedido->elementos->isNotEmpty())
                    <p><strong>Pedido:</strong> {{ $pedido->id }}</p>
                    <p><strong>Colaborador:</strong> {{ $pedido->colaborador->nombre }}</p>
                    <p><strong>Lote (Categoría):</strong> {{ $pedido->elementos->first()->categoria->nombre }}</p>
                    <p><strong>Fecha:</strong> {{ $pedido->fecha_pedido  }}</p>
                @endif
            @endforeach
            @else
         <h3>Listado de Pedidos</h3>

        @endif
        
        <table>
            <thead>
                <tr>
                    @if (!request('pedido_id'))
                    <th class="text-center">ID</th>

                    @endif

                    @if ($user->rol === 'administrador' && !request('pedido_id'))
                        <th>Colaborador</th>
                    @endif
                    @if (!request('pedido_id'))
                    <th class="text-center ancho-fecha">Fecha</th>
                        <th>Estado</th>
                    @endif
                    <th class="text-center">Elemento</th>
                    <th class="text-center">Cantidad</th>
                    @if (!request('pedido_id'))
                        <th>Lote (Categoría)</th>
                    @endif
                    <th class="text-center ancho-fecha">Valor Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pedidos as $pedido)
                    @php
                        $totalElements = count($pedido->elementos);
                    @endphp
                    @foreach ($pedido->elementos as $key => $elemento)
                        <tr>
                            @if ($key === 0)
                                @if (!request('pedido_id'))

                                <td rowspan="{{ $totalElements }}">{{ $pedido->id }}</td>
                                @endif


                                @if ($user->rol === 'administrador' && !request('pedido_id'))
                                    <td rowspan="{{ $totalElements }}">{{ $pedido->colaborador->nombre }}</td>
                                @endif
                                @if (!request('pedido_id'))
                                <td rowspan="{{ $totalElements }}">{{ $pedido->fecha_pedido }}</td>
                                    <td rowspan="{{ $totalElements }}">
                                        @if ($pedido->estado == 'pendiente')
                                            <span class="badge badge-warning">Pendiente</span>
                                        @elseif ($pedido->estado == 'aprobado')
                                            <span class="badge badge-success">Aprobado</span>
                                        @else
                                            <span class="badge badge-danger">Rechazado</span>
                                        @endif
                                    </td>
                                @endif
                            @endif
                            <td>{{ $elemento->descripcion }}</td>
                            <td class="text-center">{{ $elemento->pivot->cantidad }}</td>
                            @if (!request('pedido_id'))
                                <td>{{ $elemento->categoria->nombre }}</td>
                            @endif
                            <td class="text-right">${{ number_format($elemento->pivot->cantidad * $elemento->pivot->precio_unitario_en_pedido, 2) }}</td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="{{ $user->rol === 'administrador' ? (request('pedido_id') ? 4 : 8) : 7 }}">No hay pedidos que coincidan con los criterios.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="{{ $user->rol === 'administrador' ? (request('pedido_id') ? 2 : 7) : 6 }}">Total Reporte</th>
                    <th class="text-right">${{ number_format($total_reporte, 2) }}</th>
                </tr>
            </tfoot>
        </table>
        
        {{ $pedidos->links() }}
        
        @if ($pedidos->count() === 1)
                <br>
                <p><strong><pre>Solicitado por:     ____________________________</pre></strong> </p>
                <p><strong><pre>C.C.                ____________________________</pre></strong> </p>
                <p><strong><pre>Fecha de Solicitud: ____________________________</pre></strong> </p>

        @endif

    </div>
    
    <script>
        function printReport() {
            var printContents = document.querySelector('table').outerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = '<h1>Reporte de Pedido</h1><br>' + printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>
</body>
</html>