<?php
function index() {
    verificarSesion();
    
    $pedidoModel = new Pedido();
    $clienteModel = new Cliente();
    $productoModel = new Producto();
    
    $estadisticas = $pedidoModel->getEstadisticas();
    $pendientes = $pedidoModel->getPendientes();
    $recientes = $pedidoModel->getRecientes(5); // Últimos 5 pedidos
    
    $data = [
        'estadisticas' => $estadisticas ?: ['total' => 0, 'pendientes' => 0, 'atendidos' => 0, 'anulados' => 0],
        'pendientes' => $pendientes ?: [],
        'recientes' => $recientes ?: [],
        'totalClientes' => $clienteModel->count() ?: 0,
        'totalProductos' => $productoModel->count() ?: 0
    ];
    
    extract($data);
    require_once __DIR__ . '/../views/layouts/header.php';
    require_once __DIR__ . '/../views/dashboard.php';
    require_once __DIR__ . '/../views/layouts/footer.php';
}
?>
