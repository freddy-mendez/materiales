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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
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
                    <button class="btn btn-primary" onclick="window.print()">Imprimir en PDF</button>
                </div>
            @endif
        @endif
        
        @if ($user->rol === 'administrador' && !$is_filtered_by_pedido)
            <div class="text-right no-print" style="margin-bottom: 1rem;">
                <button id="toggle-resumen-btn" class="btn btn-primary">Mostrar Resumen por Colaborador</button>
            </div>

            <div id="resumen-colaboradores" style="display: none;">
                <br>
                <h3>Resumen por Colaborador</h3>
                <table id="resumen-table" class="display">
                    <thead>
                        <tr>
                            <th>Colaborador</th>
                            <th class="text-right">Valor Máximo</th>
                            <th class="text-right">Valor Aprobado</th>
                            <th class="text-right">Valor Pendiente</th>
                            <th class="text-right">Valor Gastado</th>
                            <th class="text-right">Valor Restante</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reporte_colaboradores as $reporte)
                            <tr>
                                <td>{{ $reporte->nombre }}</td>
                                <td class="text-right">${{ number_format($reporte->valor_maximo, 2) }}</td>
                                <td class="text-right">${{ number_format($reporte->valor_aprobado, 2) }}</td>
                                <td class="text-right">${{ number_format($reporte->valor_pendiente, 2) }}</td>
                                <td class="text-right">${{ number_format($reporte->valor_gastado, 2) }}</td>
                                <td class="text-right">${{ number_format($reporte->valor_restante, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
            </div>
        @endif

        @if ($is_filtered_by_pedido)
            @foreach ($pedidos as $pedido)
                <p><strong>Pedido:</strong> {{ $pedido->id }}</p>
                <p><strong>Colaborador:</strong> {{ $pedido->colaborador->nombre }}</p>
                <p><strong>Fecha:</strong> {{ $pedido->fecha_pedido  }}</p>
                <h3>Listado de Elementos</h3>
            @endforeach
        @else
            <h3>Listado de Pedidos</h3>
        @endif
        
        <table>
            <thead>
                <tr>
                    @if (!$is_filtered_by_pedido)
                        <th class="text-center">ID</th>
                        @if ($user->rol === 'administrador')
                            <th>Colaborador</th>
                        @endif
                        <th class="text-center ancho-fecha">Fecha</th>
                        <th>Estado</th>
                        <th class="text-center ancho-fecha">Valor Total</th>
                    @else
                        <th class="text-center">Elemento</th>
                        <th class="text-center">Cantidad</th>
                        <th>Lote (Categoría)</th>
                        <th class="text-center ancho-fecha">Valor Total</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($pedidos as $pedido)
                    @if (!$is_filtered_by_pedido)
                        <tr>
                            <td class="text-center">{{ $pedido->id }}</td>
                            @if ($user->rol === 'administrador')
                                <td>{{ $pedido->colaborador->nombre }}</td>
                            @endif
                            <td class="text-center">{{ $pedido->fecha_pedido }}</td>
                            <td>
                                @if ($pedido->estado == 'pendiente')
                                    <span class="badge badge-warning">Pendiente</span>
                                @elseif ($pedido->estado == 'aprobado')
                                    <span class="badge badge-success">Aprobado</span>
                                @else
                                    <span class="badge badge-danger">Rechazado</span>
                                @endif
                            </td>
                            <td class="text-right">${{ number_format($pedido->valor_total, 2) }}</td>
                        </tr>
                    @else
                        @foreach ($pedido->elementos as $elemento)
                            <tr>
                                <td>{{ $elemento->descripcion }}</td>
                                <td class="text-center">{{ $elemento->pivot->cantidad }}</td>
                                <td>{{ $elemento->categoria->nombre }}</td>
                                <td class="text-right">${{ number_format($elemento->pivot->cantidad * $elemento->pivot->precio_unitario_en_pedido, 2) }}</td>
                            </tr>
                        @endforeach
                    @endif
                @empty
                    <tr>
                        <td colspan="{{ $is_filtered_by_pedido ? 4 : ($user->rol === 'administrador' ? 5 : 4) }}">No hay pedidos que coincidan con los criterios.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="{{ $is_filtered_by_pedido ? 3 : ($user->rol === 'administrador' ? 4 : 3) }}">Total Reporte</th>
                    <th class="text-right">${{ number_format($total_reporte, 2) }}</th>
                </tr>
            </tfoot>
        </table>
        
        {{ $pedidos->links('vendor.pagination.custom-pagination') }}
        
        @if ($is_filtered_by_pedido && $pedidos->count() === 1)
            <br>
            <p><strong><pre>Solicitado por:     ____________________________</pre></strong> </p>
            <p><strong><pre>C.C.                ____________________________</pre></strong> </p>
            <p><strong><pre>Fecha de Solicitud: ____________________________</pre></strong> </p>
        @endif
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar DataTables
            $('#resumen-table').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                }
            });

            // Lógica para el toggle del resumen de colaboradores
            $('#toggle-resumen-btn').on('click', function() {
                var resumenDiv = $('#resumen-colaboradores');
                if (resumenDiv.is(':visible')) {
                    resumenDiv.hide();
                    $(this).text('Mostrar Resumen por Colaborador');
                } else {
                    resumenDiv.show();
                    $(this).text('Ocultar Resumen por Colaborador');
                }
            });
        });
        
        function printReport() {
            window.print();
        }
    </script>
</body>
</html>