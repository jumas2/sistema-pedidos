<?php

function index() {
    verificarSesion();
    $pedido = new Pedido();
    
    // ========================================== //
    // FILTRO POR ESTADO DESDE EL DASHBOARD     //
    // ========================================== //
    $estado = $_GET['estado'] ?? null;
    
    if ($estado) {
        // Obtener pedidos filtrados por estado
        $pedidos = $pedido->getByEstado($estado);
    } else {
        // Obtener todos los pedidos
        $pedidos = $pedido->all();
    }
    
    require_once __DIR__ . '/../views/pedidos/index.php';
}

function crear() {
    verificarSesion();
    
    $clienteModel = new Cliente();
    $productoModel = new Producto();
    $agenciaModel = new Agencia();
    $vendedorModel = new Vendedor();
    
    $clientes = $clienteModel->all();
    $productos = $productoModel->all();
    $agencias = $agenciaModel->all();
    $vendedores = $vendedorModel->all();
    $numero_pedido = generarNumeroPedido();
    
    require_once __DIR__ . '/../views/pedidos/crear.php';
}

function guardar() {
    verificarSesion();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('pedidos/crear');
        return;
    }
    
    $productos = json_decode($_POST['productos'], true);
    $moneda = $_POST['moneda'] ?? 'PEN';
    
    if (empty($productos)) {
        setFlash('danger', 'Debe agregar al menos un producto');
        redirect('pedidos/crear');
        return;
    }
    
    $subtotal = 0;
    foreach ($productos as $item) {
        $subtotal += $item['cantidad'] * $item['precio_unitario'];
    }
    $igv = $subtotal * 0.18;
    $total = $subtotal + $igv;
    
    $pedido = new Pedido();
    $data = [
        'numero_pedido' => $_POST['numero_pedido'],
        'cliente_id' => $_POST['cliente_id'],
        'vendedor_id' => $_POST['vendedor_id'] ?: null,
        'usuario_registro_id' => $_SESSION['usuario']['id'],
        'oc' => $_POST['oc'] ?: null,
        'condicion' => $_POST['condicion'],
        'fecha_pedido' => $_POST['fecha_pedido'],
        'fecha_entrega' => $_POST['fecha_entrega'] ?: null,
        'zona' => $_POST['zona'] ?? '',
        'agencia_id' => $_POST['agencia_id'] ?: null,
        'direccion_envio' => $_POST['direccion_envio'] ?? '',
        'nombre_recibe' => $_POST['nombre_recibe'] ?? '',
        'dni_recibe' => $_POST['dni_recibe'] ?? '',
        'observacion' => $_POST['observacion'] ?? '',
        'subtotal' => $subtotal,
        'igv' => $igv,
        'total' => $total,
        'moneda' => $moneda,
        'estado' => 'Pendiente'
    ];
    
    $pedido_id = $pedido->create($data);
    
    if (!$pedido_id) {
        setFlash('danger', 'Error al registrar pedido');
        redirect('pedidos/crear');
        return;
    }
    
    $detalle = new DetallePedido();
    $productoModel = new Producto();
    
    foreach ($productos as $item) {
        $detalle->create([
            'pedido_id' => $pedido_id,
            'producto_id' => $item['producto_id'],
            'cantidad' => $item['cantidad'],
            'precio_unitario' => $item['precio_unitario'],
            'monto' => $item['cantidad'] * $item['precio_unitario']
        ]);
        
        $productoModel->updateStock($item['producto_id'], $item['cantidad']);
    }
    
    setFlash('success', 'Pedido registrado exitosamente');
    redirect('pedidos/ver/' . $pedido_id);
}

function ver($id) {
    verificarSesion();
    
    $pedido = new Pedido();
    $pedidoData = $pedido->find($id);
    $detalles = $pedido->getDetalles($id);
    
    require_once __DIR__ . '/../views/pedidos/ver.php';
}

function editar($id) {
    verificarSesion();
    
    $pedido = new Pedido();
    $pedidoData = $pedido->find($id);
    
    if (!$pedidoData || $pedidoData['estado'] === 'Atendido' || $pedidoData['estado'] === 'Anulado') {
        setFlash('danger', 'No se puede editar un pedido atendido o anulado');
        redirect('pedidos/ver/' . $id);
        return;
    }
    
    $clienteModel = new Cliente();
    $productoModel = new Producto();
    $agenciaModel = new Agencia();
    $vendedorModel = new Vendedor();
    
    $clientes = $clienteModel->all();
    $productos = $productoModel->all();
    $agencias = $agenciaModel->all();
    $vendedores = $vendedorModel->all();
    $detalles = $pedido->getDetalles($id);
    
    require_once __DIR__ . '/../views/pedidos/editar.php';
}

function actualizar($id) {
    verificarSesion();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('pedidos/ver/' . $id);
        return;
    }
    
    $pedido = new Pedido();
    $pedidoData = $pedido->find($id);
    
    if (!$pedidoData || $pedidoData['estado'] === 'Atendido' || $pedidoData['estado'] === 'Anulado') {
        setFlash('danger', 'No se puede editar un pedido atendido o anulado');
        redirect('pedidos/ver/' . $id);
        return;
    }
    
    $productos = json_decode($_POST['productos'], true);
    $moneda = $_POST['moneda'] ?? 'PEN';
    
    if (empty($productos)) {
        setFlash('danger', 'Debe agregar al menos un producto');
        redirect('pedidos/editar/' . $id);
        return;
    }
    
    $subtotal = 0;
    foreach ($productos as $item) {
        $subtotal += $item['cantidad'] * $item['precio_unitario'];
    }
    $igv = $subtotal * 0.18;
    $total = $subtotal + $igv;
    
    $data = [
        'cliente_id' => $_POST['cliente_id'],
        'vendedor_id' => $_POST['vendedor_id'] ?: null,
        'oc' => $_POST['oc'] ?: null,
        'condicion' => $_POST['condicion'],
        'fecha_pedido' => $_POST['fecha_pedido'],
        'fecha_entrega' => $_POST['fecha_entrega'] ?: null,
        'zona' => $_POST['zona'] ?? '',
        'agencia_id' => $_POST['agencia_id'] ?: null,
        'direccion_envio' => $_POST['direccion_envio'] ?? '',
        'nombre_recibe' => $_POST['nombre_recibe'] ?? '',
        'dni_recibe' => $_POST['dni_recibe'] ?? '',
        'observacion' => $_POST['observacion'] ?? '',
        'subtotal' => $subtotal,
        'igv' => $igv,
        'total' => $total,
        'moneda' => $moneda
    ];
    
    if ($pedido->update($id, $data)) {
        $detalle = new DetallePedido();
        $detalle->deleteByPedido($id);
        
        $productoModel = new Producto();
        foreach ($productos as $item) {
            $detalle->create([
                'pedido_id' => $id,
                'producto_id' => $item['producto_id'],
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio_unitario'],
                'monto' => $item['cantidad'] * $item['precio_unitario']
            ]);
            
            $productoModel->updateStock($item['producto_id'], $item['cantidad']);
        }
        
        setFlash('success', 'Pedido actualizado exitosamente');
        redirect('pedidos/ver/' . $id);
    } else {
        setFlash('danger', 'Error al actualizar pedido');
        redirect('pedidos/editar/' . $id);
    }
}

function anular($id) {
    verificarRol(['admin', 'logistica']);
    
    $pedido = new Pedido();
    $pedidoData = $pedido->find($id);
    
    if (!$pedidoData) {
        setFlash('danger', 'Pedido no encontrado');
        redirect('pedidos');
        return;
    }
    
    if ($pedidoData['estado'] === 'Anulado') {
        setFlash('warning', 'El pedido ya está anulado');
        redirect('pedidos/ver/' . $id);
        return;
    }
    
    if ($pedidoData['estado'] === 'Atendido') {
        setFlash('danger', 'No se puede anular un pedido atendido');
        redirect('pedidos/ver/' . $id);
        return;
    }
    
    if ($pedido->anular($id, $_SESSION['usuario']['id'])) {
        setFlash('success', 'Pedido anulado correctamente');
    } else {
        setFlash('danger', 'Error al anular pedido');
    }
    redirect('pedidos/ver/' . $id);
}

function duplicar($id) {
    verificarSesion();
    
    $pedido = new Pedido();
    $nuevo_id = $pedido->clone($id);
    
    if ($nuevo_id) {
        setFlash('success', 'Pedido duplicado exitosamente');
        redirect('pedidos/ver/' . $nuevo_id);
    } else {
        setFlash('danger', 'Error al duplicar pedido');
        redirect('pedidos/ver/' . $id);
    }
}

function cambiarEstado($id) {
    verificarRol(['admin', 'logistica']);
    
    $estado = $_POST['estado'] ?? 'Atendido';
    $pedido = new Pedido();
    
    if ($pedido->updateEstado($id, $estado, $_SESSION['usuario']['id'])) {
        setFlash('success', 'Estado actualizado');
    } else {
        setFlash('danger', 'Error al actualizar estado');
    }
    redirect('pedidos/ver/' . $id);
}

function eliminar($id) {
    verificarRol(['admin']);
    
    $pedido = new Pedido();
    $pedidoData = $pedido->find($id);
    
    if (!$pedidoData) {
        setFlash('danger', 'Pedido no encontrado');
        redirect('pedidos');
        return;
    }
    
    if ($pedidoData['estado'] === 'Atendido') {
        setFlash('danger', 'No se puede eliminar un pedido atendido');
        redirect('pedidos/ver/' . $id);
        return;
    }
    
    if ($pedido->delete($id)) {
        setFlash('success', 'Pedido eliminado correctamente');
    } else {
        setFlash('danger', 'Error al eliminar pedido');
    }
    redirect('pedidos');
}

// ============================================
// TOOLTIP
// ============================================
function getDetalleTooltip($id) {
    verificarSesion();
    
    $pedido = new Pedido();
    $pedidoData = $pedido->find($id);
    $detalles = $pedido->getDetalles($id);
    
    if (!$pedidoData) {
        echo json_encode(['success' => false, 'message' => 'Pedido no encontrado']);
        exit;
    }
    
    $items = [];
    foreach ($detalles as $item) {
        $items[] = [
            'nombre' => $item['producto_nombre'],
            'cantidad' => $item['cantidad'],
            'subtotal' => number_format($item['monto'], 2),
            'codigo' => $item['codigo']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'id' => $pedidoData['id'],
        'numero' => $pedidoData['numero_pedido'],
        'cliente' => $pedidoData['cliente_nombre'],
        'ruc' => $pedidoData['ruc_dni'] ?? 'Sin RUC',
        'fecha' => date('d/m/Y H:i', strtotime($pedidoData['fecha_pedido'])),
        'total' => number_format($pedidoData['total'], 2),
        'estado' => $pedidoData['estado'],
        'condicion' => $pedidoData['condicion'],
        'zona' => $pedidoData['zona'] ?? 'No especificada',
        'observacion' => nl2br(htmlspecialchars($pedidoData['observacion'] ?? '')),
        'moneda' => $pedidoData['moneda'] ?? 'PEN',
        'items' => $items
    ]);
    exit;
}
?>
