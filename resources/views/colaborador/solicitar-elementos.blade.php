<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Elementos</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 2rem;
        }

        .form-container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1,
        h2 {
            text-align: center;
        }

        .balance-info {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1rem;
            background-color: #e9ecef;
            border-radius: 8px;
        }

        .balance-info p {
            font-size: 1.2rem;
            margin: 0;
        }

        .element-input-row {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.5rem;
            border: 1px solid #eee;
            border-radius: 4px;
        }

        .element-input-row select.elemento-select {
            flex: 2;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .element-input-row input.cantidad-input {
            flex: 1;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .add-btn {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            padding: 0.75rem 1rem;
            border-radius: 4px;
        }

        .remove-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            color: white;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            cursor: pointer;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .error {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .total-summary {
            margin-top: 1.5rem;
        }

        .total-summary table {
            width: 100%;
        }

        .total-summary th,
        .total-summary td {
            border: none;
            padding: 0.5rem;
        }

        .total-summary th {
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
        }

        .text-right {
            text-align: right;
        }

        .ancho {
            width: 50%;
            margin-left: auto;
            margin-right: 0;
        }

        .centro {
            width: 70%;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h1>Solicitar Elementos</h1>
        <p><a href="{{ route('colaborador.dashboard') }}" class="btn btn-secondary">Volver al Dashboard</a></p>

        <div class="balance-info">
            <table class="centro">
                <tbody>
                    <tr>
                        <td><strong>Tu Valor Máximo:</strong></td>
                        <td class="text-right"><span>${{ number_format($colaborador->valor_maximo_dinero, 2) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Valor Pendiente:</strong></td>
                        <td class="text-right"><span>${{ number_format($valor_pendiente, 2) }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Tu Saldo Disponible:</strong></td>
                        <td class="text-right"><span
                                id="valor-disponible">${{ number_format($valor_disponible, 2) }}</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('colaborador.crear-pedido') }}" method="POST">
            @csrf
            <h3>Añadir Elemento</h3>
            <div class="element-input-row">
                <select id="elemento-select" class="elemento-select" required onchange="showDescription(this)">
                    <option value="">Selecciona un elemento</option>
                    @foreach ($elementos as $elemento)
                        <option value="{{ $elemento->id }}" data-descripcion="{{ $elemento->descripcion }}"
                            data-precio="{{ $elemento->precio_unitario }}">
                            {{ $elemento->nombre }} (${{ number_format($elemento->precio_unitario, 2) }})
                            @if($elemento->unidad_de_medida)
                                ({{ $elemento->unidad_de_medida }})
                            @endif
                        </option>
                    @endforeach
                </select>
                <input type="number" id="cantidad-input" class="cantidad-input" placeholder="Cantidad" min="1" value="1"
                    required>
                <button type="button" class="add-btn" onclick="addItemToTable()">Agregar</button>
            </div>
            
            <div id="descripcion-elemento" style="margin-top: 1rem; padding: 0.5rem; border-left: 4px solid #007bff; background-color: #f8f9fa;">
                <p style="margin: 0; font-style: italic;">Selecciona un elemento para ver su descripción.</p>
            </div>

            <h3>Elementos del Pedido</h3>
            <table id="pedido-table">
                <thead>
                    <tr>
                        <th>Elemento</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <table class="ancho">
                <tbody>
                    <tr>
                        <td>Total del Pedido:</td>
                        <td class="text-right"><span id="total-pedido">$0.00</span></td>
                    </tr>
                    <tr>
                        <td>Saldo Restante:</td>
                        <td class="text-right"><span
                                id="saldo-restante">${{ number_format($valor_disponible, 2) }}</span></td>
                    </tr>
                </tbody>
            </table>

            <br>
            <input type="hidden" name="fecha_pedido" value="{{ date('Y-m-d') }}">
            <button type="button" class="btn btn-primary" id="submit-btn" onclick="handleFormSubmission()" disabled>Enviar Solicitud</button>
            <a href="{{ route('colaborador.dashboard') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const elementos = @json($elementos->keyBy('id'));
        const valorDisponibleInicial = {{ $valor_disponible }};
        let itemCounter = 0;

        function formatCurrency(value) {
            return `$${parseFloat(value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}`;
        }

        function calculateTotals() {
            let totalPedido = 0;
            const subtotalElements = document.querySelectorAll('.subtotal-item');
            subtotalElements.forEach(element => {
                totalPedido += parseFloat(element.dataset.value);
            });

            const saldoRestante = valorDisponibleInicial - totalPedido;

            document.getElementById('total-pedido').innerText = formatCurrency(totalPedido);
            document.getElementById('saldo-restante').innerText = formatCurrency(saldoRestante);

            const saldoRestanteElement = document.getElementById('saldo-restante');
            const submitButton = document.getElementById('submit-btn');

            if (saldoRestante < 0) {
                saldoRestanteElement.style.color = 'red';
                submitButton.disabled = true;
            } else {
                saldoRestanteElement.style.color = 'black';
                submitButton.disabled = false;
            }

            if (subtotalElements.length === 0) {
                submitButton.disabled = true;
            }
        }

        function addItemToTable() {
            const select = document.getElementById('elemento-select');
            const cantidadInput = document.getElementById('cantidad-input');
            const elementoId = select.value;
            const cantidad = parseInt(cantidadInput.value, 10);

            if (!elementoId || cantidad <= 0 || isNaN(cantidad)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Solicitud',
                    text: 'Por favor, selecciona un elemento y una cantidad válida.',
                });
                return;
            }

            const elemento = elementos[elementoId];
            const tableBody = document.querySelector('#pedido-table tbody');

            // Buscar si el elemento ya existe en la tabla
            const existingRow = tableBody.querySelector(`tr[data-elemento-id="${elementoId}"]`);

            if (existingRow) {
                // Si el elemento existe, actualizar la cantidad y el subtotal
                const currentCantidadCell = existingRow.querySelector('.cantidad-item');
                const currentSubtotalCell = existingRow.querySelector('.subtotal-item');
                const hiddenCantidadInput = existingRow.querySelector(`input[name*="[cantidad]"]`);

                const nuevaCantidad = parseInt(currentCantidadCell.innerText, 10) + cantidad;
                const nuevoSubtotal = nuevaCantidad * parseFloat(elemento.precio_unitario);

                currentCantidadCell.innerText = nuevaCantidad;
                currentSubtotalCell.dataset.value = nuevoSubtotal;
                currentSubtotalCell.innerText = formatCurrency(nuevoSubtotal);
                hiddenCantidadInput.value = nuevaCantidad;

            } else {
                // Si el elemento no existe, agregar una nueva fila
                const newRow = tableBody.insertRow();
                const subtotal = parseFloat(elemento.precio_unitario) * cantidad;

                newRow.dataset.elementoId = elementoId;

                newRow.innerHTML = `
                    <td>${elemento.descripcion}</td>
                    <td class="cantidad-item">${cantidad}</td>
                    <td class="text-right">${formatCurrency(elemento.precio_unitario)}</td>
                    <td class="subtotal-item text-right" data-value="${subtotal}">${formatCurrency(subtotal)}</td>
                    <td><button type="button" class="remove-btn" onclick="this.closest('tr').remove(); calculateTotals();">Quitar</button></td>
                    <input type="hidden" name="elementos[${itemCounter}][elemento_id]" value="${elementoId}">
                    <input type="hidden" type="hidden" name="elementos[${itemCounter}][cantidad]" value="${cantidad}">
                `;
                itemCounter++;
            }

            calculateTotals();
            // Limpiar el campo de cantidad y resetear el select para una nueva selección
            select.value = "";
            cantidadInput.value = 1;
        }

        function showDescription(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const descripcionDiv = document.getElementById('descripcion-elemento');
            
            if (selectedOption.dataset.descripcion) {
                descripcionDiv.innerHTML = `<p style="margin: 0;"><strong>Descripción:</strong> ${selectedOption.dataset.descripcion}</p>`;
            } else {
                descripcionDiv.innerHTML = `<p style="margin: 0; font-style: italic;">Selecciona un elemento para ver su descripción.</p>`;
            }
        }

        function handleFormSubmission() {
            // Recalcular el valor total del pedido
            let totalPedido = 0;
            const subtotalElements = document.querySelectorAll('.subtotal-item');
            subtotalElements.forEach(element => {
                totalPedido += parseFloat(element.dataset.value);
            });

            // Verificar el saldo restante
            const saldoRestante = valorDisponibleInicial - totalPedido;
            if (saldoRestante < 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Saldo Insuficiente',
                    text: 'El valor total de este pedido (' + formatCurrency(totalPedido) + ') excede tu saldo disponible. No se puede enviar el pedido.',
                });
                return;
            }

            // Mostrar el modal de SweetAlert para confirmar la solicitud
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'El valor total de tu pedido es ' + formatCurrency(totalPedido) + '.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, enviar solicitud',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, envía el formulario
                    document.querySelector('form').submit();
                }
            });
        }
    </script>
</body>

</html>