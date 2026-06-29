<?php

// ============================================
// LOGÍSTICA - DESPACHOS Y VEHÍCULOS
// ============================================

function index() {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica', 'asistente_logistica']);
    
    $estadisticas = getEstadisticas();
    $guiaModel = new GuiaRemision();
    $transportistaModel = new Transportista();
    $pedidoModel = new Pedido();
    
    $pendientes = $guiaModel->getPendientes();
    $guias = $guiaModel->all();
    $transportistas = $transportistaModel->all();
    $pedidosPendientes = $pedidoModel->getPendientes();
    
    require_once __DIR__ . '/../views/logistica/index.php';
}

function crearGuia() {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('?url=logistica');
        return;
    }
    
    $data = [
        'numero_guia' => trim($_POST['numero_guia'] ?? ''),
        'pedido_id' => $_POST['pedido_id'],
        'transportista_id' => $_POST['transportista_id'] ?: null,
        'fecha_emision' => $_POST['fecha_emision'],
        'direccion_entrega' => trim($_POST['direccion_entrega'] ?? ''),
        'observacion' => trim($_POST['observacion'] ?? '')
    ];
    
    $guia = new GuiaRemision();
    if ($guia->create($data)) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE pedidos SET estado_despacho = 'Pendiente' WHERE id = :id");
        $stmt->execute(['id' => $data['pedido_id']]);
        
        setFlash('success', 'Guía de remisión creada correctamente');
    } else {
        setFlash('danger', 'Error al crear la guía');
    }
    redirect('?url=logistica');
}

function ver($id) {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica', 'asistente_logistica', 'vendedor', 'conductor']);
    
    $guiaModel = new GuiaRemision();
    $despachoModel = new Despacho();
    $transportistaModel = new Transportista();
    $vehiculoModel = new Vehiculo();
    
    $guia = $guiaModel->find($id);
    $seguimiento = $despachoModel->getByGuia($id);
    $transportistas = $transportistaModel->all();
    $vehiculosDisponibles = $vehiculoModel->getDisponibles();
    
    require_once __DIR__ . '/../views/logistica/ver.php';
}

function actualizarEstado($id) {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('?url=logistica/ver/' . $id);
        return;
    }
    
    $estado = $_POST['estado'] ?? 'Pendiente';
    $observacion = trim($_POST['observacion'] ?? '');
    $ubicacion = trim($_POST['ubicacion'] ?? '');
    
    $guiaModel = new GuiaRemision();
    $despachoModel = new Despacho();
    
    $guiaModel->updateEstado($id, $estado);
    
    $despachoModel->create([
        'guia_id' => $id,
        'estado' => $estado,
        'ubicacion' => $ubicacion,
        'observacion' => $observacion,
        'usuario_registro_id' => $_SESSION['usuario']['id']
    ]);
    
    $guia = $guiaModel->find($id);
    if ($guia) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE pedidos SET estado_despacho = :estado WHERE id = :pedido_id");
        $stmt->execute(['estado' => $estado, 'pedido_id' => $guia['pedido_id']]);
    }
    
    setFlash('success', 'Estado actualizado correctamente');
    redirect('?url=logistica/ver/' . $id);
}

function misDespachos() {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica', 'asistente_logistica', 'vendedor', 'conductor']);
    
    $db = Database::getConnection();
    $stmt = $db->prepare("SELECT id FROM transportistas WHERE usuario_id = :usuario_id AND activo = 1");
    $stmt->execute(['usuario_id' => $_SESSION['usuario']['id']]);
    $transportista = $stmt->fetch();
    
    if (!$transportista) {
        setFlash('info', 'No tienes un transportista asignado. Contacta al administrador.');
        $guiaModel = new GuiaRemision();
        $misGuias = $guiaModel->all();
    } else {
        $guiaModel = new GuiaRemision();
        $misGuias = $guiaModel->getByTransportista($transportista['id']);
    }
    
    require_once __DIR__ . '/../views/logistica/mis_despachos.php';
}

function asignarTransportista() {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('?url=logistica');
        return;
    }
    
    $guia_id = $_POST['guia_id'];
    $transportista_id = $_POST['transportista_id'];
    
    $guiaModel = new GuiaRemision();
    $guia = $guiaModel->find($guia_id);
    
    if ($guia) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE guias_remision SET transportista_id = :transportista_id WHERE id = :id");
        $stmt->execute(['transportista_id' => $transportista_id, 'id' => $guia_id]);
        
        setFlash('success', 'Transportista asignado correctamente');
    } else {
        setFlash('danger', 'Error al asignar transportista');
    }
    redirect('?url=logistica/ver/' . $guia_id);
}

// ============================================
// GESTIÓN DE VEHÍCULOS
// ============================================

function vehiculos() {
    $estado = $_GET["estado"] ?? "activos";
    $vehiculoModel = new Vehiculo();
    if ($estado === "inactivos") {
        $vehiculos = $vehiculoModel->allInactivos();
    } else {
        $vehiculos = $vehiculoModel->all();
    }
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    
    $vehiculoModel = new Vehiculo();
    $vehiculos = $vehiculoModel->all();
    
    require_once __DIR__ . '/../views/logistica/vehiculos.php';
}

function crearVehiculo() {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('?url=logistica/vehiculos');
        return;
    }
    
    $data = [
        'placa' => strtoupper(trim($_POST['placa'] ?? '')),
        'marca' => strtoupper(trim($_POST['marca'] ?? '')),
        'modelo' => strtoupper(trim($_POST['modelo'] ?? '')),
        'anio' => intval($_POST['anio'] ?? 0),
        'color' => strtoupper(trim($_POST['color'] ?? '')),
        'tipo' => strtoupper(trim($_POST['tipo'] ?? '')),
        'capacidad_carga' => trim($_POST['capacidad_carga'] ?? ''),
        'estado' => $_POST['estado'] ?? 'Disponible',
        'observacion' => trim($_POST['observacion'] ?? '')
    ];
    
    $vehiculo = new Vehiculo();
    if ($vehiculo->create($data)) {
        setFlash('success', 'Vehículo registrado correctamente');
    } else {
        setFlash('danger', 'Error al registrar vehículo');
    }
    redirect('?url=logistica/vehiculos');
}

function actualizarVehiculo($id) {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('?url=logistica/vehiculos');
        return;
    }
    
    $data = [
        'placa' => strtoupper(trim($_POST['placa'] ?? '')),
        'marca' => strtoupper(trim($_POST['marca'] ?? '')),
        'modelo' => strtoupper(trim($_POST['modelo'] ?? '')),
        'anio' => intval($_POST['anio'] ?? 0),
        'color' => strtoupper(trim($_POST['color'] ?? '')),
        'tipo' => strtoupper(trim($_POST['tipo'] ?? '')),
        'capacidad_carga' => trim($_POST['capacidad_carga'] ?? ''),
        'estado' => $_POST['estado'] ?? 'Disponible',
        'observacion' => trim($_POST['observacion'] ?? '')
    ];
    
    $vehiculo = new Vehiculo();
    if ($vehiculo->update($id, $data)) {
        setFlash('success', 'Vehículo actualizado correctamente');
    } else {
        setFlash('danger', 'Error al actualizar vehículo');
    }
    redirect('?url=logistica/vehiculos');
}

function eliminarVehiculo($id) {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    
    $vehiculo = new Vehiculo();
    if ($vehiculo->delete($id)) {
        setFlash('success', 'Vehículo eliminado correctamente');
    } else {
        setFlash('danger', 'Error al eliminar vehículo');
    }
    redirect('?url=logistica/vehiculos');
}

// ============================================
// GESTIÓN DE TRANSPORTISTAS
// ============================================

function transportistas() {
    $estado = $_GET["estado"] ?? "activos";
    $transportistaModel = new Transportista();
    if ($estado === "inactivos") {
        $transportistas = $transportistaModel->allInactivos();
    } else {
        $transportistas = $transportistaModel->all();
    }
    $mostrar = $_GET["mostrar"] ?? "activos";
    $transportistaModel = new Transportista();
    if ($mostrar === "inactivos") {
        $transportistas = $transportistaModel->allInactivos();
    } else {
        $transportistas = $transportistaModel->all();
    }
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    
    $transportistaModel = new Transportista();
    $transportistas = $transportistaModel->all();
    $vehiculoModel = new Vehiculo();
    $vehiculos = $vehiculoModel->all();
    $usuarioModel = new Usuario();
    $usuariosLogistica = $usuarioModel->getLogistica();
    $conductores = $usuarioModel->getConductores();
    
    // Combinar usuarios de logística y conductores
    $usuariosDisponibles = array_merge($usuariosLogistica, $conductores);
    // Eliminar duplicados por ID
    $usuariosDisponibles = array_unique($usuariosDisponibles, SORT_REGULAR);
    
    require_once __DIR__ . '/../views/logistica/transportistas.php';
}

function crearTransportista() {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('?url=logistica/transportistas');
        return;
    }
    
    $usuario_id = $_POST['usuario_id'] ?? null;
    $nombre = strtoupper(trim($_POST['nombre'] ?? ''));
    $apellido = strtoupper(trim($_POST['apellido'] ?? ''));
    $dni = trim($_POST['dni'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $licencia = strtoupper(trim($_POST['licencia'] ?? ''));
    
    $transportista = new Transportista();
    $usuarioModel = new Usuario();
    
    // Si se seleccionó un usuario, crear transportista desde usuario
    if ($usuario_id) {
        $result = $usuarioModel->crearTransportista($usuario_id);
        if ($result['success']) {
            setFlash('success', $result['message']);
        } else {
            setFlash('danger', $result['message']);
        }
        redirect('?url=logistica/transportistas');
        return;
    }
    
    // Si no se seleccionó usuario, crear transportista normal
    $data = [
        'nombre' => $nombre,
        'apellido' => $apellido,
        'dni' => $dni,
        'telefono' => $telefono,
        'licencia' => $licencia
    ];
    
    $result = $transportista->create($data);
    
    if ($result['success']) {
        setFlash('success', 'Transportista registrado correctamente');
    } else {
        setFlash('danger', $result['message']);
    }
    redirect('?url=logistica/transportistas');
}

function editarTransportista($id) {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('?url=logistica/transportistas');
        return;
    }
    
    $data = [
        'nombre' => strtoupper(trim($_POST['nombre'] ?? '')),
        'apellido' => strtoupper(trim($_POST['apellido'] ?? '')),
        'dni' => trim($_POST['dni'] ?? ''),
        'telefono' => trim($_POST['telefono'] ?? ''),
        'licencia' => strtoupper(trim($_POST['licencia'] ?? '')),
        'activo' => isset($_POST['activo']) ? 1 : 0
    ];
    
    $usuario_id = $_POST['usuario_id'] ?? null;
    
    $transportista = new Transportista();
    $result = $transportista->update($id, $data);
    
    if ($result['success']) {
        if ($usuario_id) {
            $transportista->asignarUsuario($id, $usuario_id);
        }
        setFlash('success', 'Transportista actualizado correctamente');
    } else {
        setFlash('danger', $result['message']);
    }
    redirect('?url=logistica/transportistas');
}

function eliminarTransportista($id) {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    
    $transportista = new Transportista();
    if ($transportista->delete($id)) {
        setFlash('success', 'Transportista eliminado correctamente');
    } else {
        setFlash('danger', 'Error al eliminar transportista');
    }
    redirect('?url=logistica/transportistas');
}

function reactivarTransportista($id) {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    
    $transportista = new Transportista();
    if ($transportista->cambiarEstado($id, 1)) {
        setFlash('success', 'Transportista reactivado correctamente');
    } else {
        setFlash('danger', 'Error al reactivar transportista');
    }
    redirect('?url=logistica/transportistas');
}

// ============================================
// ASIGNACIÓN DE VEHÍCULOS
// ============================================

function asignarVehiculoTransportista() {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('?url=logistica/transportistas');
        return;
    }
    
    $transportista_id = $_POST['transportista_id'] ?? 0;
    $vehiculo_id = $_POST['vehiculo_id'] ?? 0;
    $tipo_asignacion = $_POST['tipo_asignacion'] ?? 'temporal';
    
    if (empty($transportista_id) || empty($vehiculo_id)) {
        setFlash('danger', 'Datos incompletos para asignar vehículo');
        redirect('?url=logistica/transportistas');
        return;
    }
    
    $transportista = new Transportista();
    $result = $transportista->asignarVehiculo($transportista_id, $vehiculo_id, $tipo_asignacion);
    
    if ($result) {
        setFlash('success', 'Vehículo asignado correctamente al transportista');
    } else {
        setFlash('danger', 'El vehículo no está disponible o ocurrió un error');
    }
    redirect('?url=logistica/transportistas');
}

function liberarVehiculoTransportista($transportista_id) {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    
    $transportista = new Transportista();
    if ($transportista->liberarVehiculo($transportista_id)) {
        setFlash('success', 'Vehículo liberado correctamente');
    } else {
        setFlash('danger', 'Error al liberar vehículo');
    }
    redirect('?url=logistica/transportistas');
}

function asignarVehiculoGuia() {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('?url=logistica');
        return;
    }
    
    $guia_id = $_POST['guia_id'];
    $vehiculo_id = $_POST['vehiculo_id'];
    
    $transportista = new Transportista();
    $result = $transportista->asignarVehiculoAGuia($guia_id, $vehiculo_id);
    
    if ($result) {
        setFlash('success', 'Vehículo asignado correctamente a la guía');
    } else {
        setFlash('danger', 'El vehículo no está disponible o ocurrió un error');
    }
    redirect('?url=logistica/ver/' . $guia_id);
}

function liberarVehiculoGuia($guia_id) {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    
    $transportista = new Transportista();
    if ($transportista->liberarVehiculoDeGuia($guia_id)) {
        setFlash('success', 'Vehículo liberado correctamente de la guía');
    } else {
        setFlash('danger', 'Error al liberar vehículo de la guía');
    }
    redirect('?url=logistica/ver/' . $guia_id);
}

function historialVehiculos($transportista_id) {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    
    $transportista = new Transportista();
    $historial = $transportista->getHistorialAsignaciones($transportista_id);
    $transportistaData = $transportista->find($transportista_id);
    
    require_once __DIR__ . '/../views/logistica/historial_vehiculos.php';
}

function confirmarEntrega($guia_id) {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica', 'asistente_logistica', 'vendedor', 'conductor']);
    
    $guiaModel = new GuiaRemision();
    $despachoModel = new Despacho();
    $guia = $guiaModel->find($guia_id);
    
    if (!$guia) {
        setFlash('danger', 'Guía no encontrada');
        redirect('?url=logistica/misDespachos');
        return;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $estado = $_POST['estado'] ?? 'Entregado';
        $observacion = trim($_POST['observacion'] ?? '');
        $recibe_nombre = trim($_POST['recibe_nombre'] ?? '');
        $recibe_dni = trim($_POST['recibe_dni'] ?? '');
        $recibido = $_POST['recibido'] ?? 1;
        
        $guiaModel->updateEstado($guia_id, $estado);
        
        $despachoModel->create([
            'guia_id' => $guia_id,
            'estado' => $estado,
            'ubicacion' => $recibe_nombre ? "Entregado a: $recibe_nombre" : 'Entrega confirmada',
            'observacion' => $observacion . ($recibe_dni ? " - DNI: $recibe_dni" : ''),
            'usuario_registro_id' => $_SESSION['usuario']['id']
        ]);
        
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE guias_remision SET fecha_entrega = CURDATE() WHERE id = :id");
        $stmt->execute(['id' => $guia_id]);
        
        $stmt = $db->prepare("UPDATE pedidos SET estado_despacho = :estado WHERE id = :pedido_id");
        $stmt->execute(['estado' => $estado, 'pedido_id' => $guia['pedido_id']]);
        
        setFlash('success', 'Entrega confirmada correctamente');
        redirect('?url=logistica/misDespachos');
        return;
    }
    
    require_once __DIR__ . '/../views/logistica/confirmar_entrega.php';
}

// ============================================
// DASHBOARD DE LOGÍSTICA - ESTADÍSTICAS
// ============================================

function getEstadisticas() {
    $db = Database::getConnection();
    
    $stmt = $db->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN estado = 'Pendiente' THEN 1 ELSE 0 END) as pendientes,
            SUM(CASE WHEN estado = 'En Proceso' THEN 1 ELSE 0 END) as en_proceso,
            SUM(CASE WHEN estado = 'Entregado' THEN 1 ELSE 0 END) as entregados,
            SUM(CASE WHEN estado = 'Cancelado' THEN 1 ELSE 0 END) as cancelados
        FROM guias_remision
    ");
    $estadisticas = $stmt->fetch();
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM transportistas WHERE activo = 1");
    $transportistas = $stmt->fetch();
    $estadisticas['transportistas'] = $transportistas['total'] ?? 0;
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM vehiculos WHERE estado = 'Disponible'");
    $vehiculos = $stmt->fetch();
    $estadisticas['vehiculos_disponibles'] = $vehiculos['total'] ?? 0;
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM vehiculos WHERE estado = 'En Uso'");
    $vehiculos = $stmt->fetch();
    $estadisticas['vehiculos_en_uso'] = $vehiculos['total'] ?? 0;
    
    $stmt = $db->query("
        SELECT COUNT(*) as total 
        FROM guias_remision 
        WHERE MONTH(fecha_emision) = MONTH(CURDATE()) 
        AND YEAR(fecha_emision) = YEAR(CURDATE())
    ");
    $mes = $stmt->fetch();
    $estadisticas['guias_mes'] = $mes['total'] ?? 0;
    
    $stmt = $db->query("
        SELECT COUNT(*) as total 
        FROM guias_remision 
        WHERE estado = 'Entregado' 
        AND DATE(fecha_entrega) = CURDATE()
    ");
    $hoy = $stmt->fetch();
    $estadisticas['entregados_hoy'] = $hoy['total'] ?? 0;
    
    return $estadisticas;
}
function reactivarVehiculo($id) {
    verificarSesion();
    verificarRol(['super_admin', 'admin', 'admin_logistica']);
    $vehiculo = new Vehiculo();
    if ($vehiculo->cambiarEstado($id, 1)) {
        setFlash('success', 'Vehículo reactivado correctamente');
    } else {
        setFlash('danger', 'Error al reactivar vehículo');
    }
    redirect('?url=logistica/vehiculos');
}
?>
