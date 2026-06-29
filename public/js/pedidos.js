$(document).ready(function() {
    console.log('✅ pedidos.js cargado e inicializado de forma segura.');
    
    // Si usas DataTables en el listado principal (index.php), se inicializa aquí de forma aislada:
    if ($('#tablaPedidos').length) {
        $('#tablaPedidos').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
            }
        });
    }
});
