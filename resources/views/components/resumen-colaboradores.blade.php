<div class="text-right no-print" style="margin-bottom: 1rem;">
    <button id="show-report-btn" class="btn btn-primary">Ver Resumen por Colaborador</button>
</div>

@props(['reporteColaboradores'])

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('show-report-btn').addEventListener('click', () => {
        const colaboradores = @json($reporteColaboradores);
        const itemsPerPage = 10;
        let currentPage = 1;

        const renderTable = (data, page) => {
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedData = data.slice(start, end);

            let tableHtml = `
                <style>
                    .swal2-container table { width: 100%; border-collapse: collapse; margin-top: 1rem; font-size: 0.9rem; }
                    .swal2-container th, .swal2-container td { padding: 0.5rem; border: 1px solid #dee2e6; text-align: left; }
                    .swal2-container th { background-color: #f8f9fa; }
                    .swal2-container .text-right { text-align: right; }
                    .swal2-container .pagination-controls { text-align: center; margin-top: 1rem; }
                    .swal2-container .pagination-controls button { padding: 0.25rem 0.5rem; margin: 0 0.2rem; cursor: pointer; }
                </style>
                <table>
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
                        ${paginatedData.map(col => `
                            <tr>
                                <td>${col.nombre}</td>
                                <td class="text-right">$${parseFloat(col.valor_maximo).toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                                <td class="text-right">$${parseFloat(col.valor_aprobado).toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                                <td class="text-right">$${parseFloat(col.valor_pendiente).toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                                <td class="text-right">$${parseFloat(col.valor_gastado).toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                                <td class="text-right">$${parseFloat(col.valor_restante).toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
            return tableHtml;
        };

        const showModal = (page) => {
            const totalPages = Math.ceil(colaboradores.length / itemsPerPage);
            const tableContent = renderTable(colaboradores, page);

            Swal.fire({
                title: 'Resumen por Colaborador',
                width: '850px',
                html: `
                    ${tableContent}
                    <div class="pagination-controls">
                        <button id="prevBtn" ${page === 1 ? 'disabled' : ''}>Anterior</button>
                        <span>Página ${page} de ${totalPages}</span>
                        <button id="nextBtn" ${page === totalPages ? 'disabled' : ''}>Siguiente</button>
                    </div>
                `,
                showConfirmButton: false,
                showCloseButton: true,
                didOpen: () => {
                    document.getElementById('prevBtn').addEventListener('click', () => {
                        if (currentPage > 1) {
                            currentPage--;
                            showModal(currentPage);
                        }
                    });
                    document.getElementById('nextBtn').addEventListener('click', () => {
                        if (currentPage < totalPages) {
                            currentPage++;
                            showModal(currentPage);
                        }
                    });
                }
            });
        };

        showModal(currentPage);
    });
</script>