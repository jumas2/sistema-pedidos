$(document).ready(function() {
    // ============================================
    // DATATABLES - Solo para tablas con clase 'datatable'
    // y que NO sean #tablaPedidos
    // ============================================
    if ($.fn.DataTable) {
        $('.table.datatable').each(function() {
            // Excluir explícitamente #tablaPedidos
            if ($(this).attr('id') !== 'tablaPedidos') {
                if (!$.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable({
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                        },
                        responsive: true,
                        pageLength: 10
                    });
                }
            }
        });
    }
    
    // ============================================
    // CONFIRMACIÓN PARA ELIMINAR
    // ============================================
    // $('.btn-danger').on('click', function(e) {
        if (!confirm('¿Estás seguro de eliminar este registro?')) {
            e.preventDefault();
        }
    });
});
