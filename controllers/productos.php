<?php

function index() {
    verificarSesion();
    $producto = new Producto();
    $productos = $producto->all();
    require_once __DIR__ . "/../views/productos/index.php";
}

function crear() {
    verificarSesion();
    
    $categoriaModel = new Categoria();
    $categorias = $categoriaModel->all();
    
    $lineaModel = new Linea();
    $lineas = $lineaModel->all();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $producto = new Producto();
        $data = [
            'codigo' => strtoupper(trim($_POST['codigo'] ?? '')),
            'nombre' => strtoupper(trim($_POST['nombre'] ?? '')),
            'descripcion' => strtoupper(trim($_POST['descripcion'] ?? '')),
            'categoria_id' => !empty($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : null,
            'linea_id' => !empty($_POST['linea_id']) ? (int)$_POST['linea_id'] : null,
            'unidad_medida' => $_POST['unidad_medida'] ?? 'UNIDAD',
            'precio' => floatval($_POST['precio'] ?? 0),
            'stock' => intval($_POST['stock'] ?? 0),
            'stock_minimo' => intval($_POST['stock_minimo'] ?? 0),
            'ubicacion' => strtoupper(trim($_POST['ubicacion'] ?? '')),
            'activo' => 1,
            'moneda' => $_POST['moneda'] ?? 'PEN'
        ];
        
        if ($producto->create($data)) {
            setFlash('success', 'Producto registrado exitosamente');
            redirect('?url=productos');
        } else {
            setFlash('danger', 'Error al registrar producto');
        }
    }
    
    require_once __DIR__ . '/../views/productos/crear.php';
}

function editar($id) {
    verificarSesion();
    $producto = new Producto();
    $productoData = $producto->find($id);
    
    $categoriaModel = new Categoria();
    $categorias = $categoriaModel->all();
    
    $lineaModel = new Linea();
    $lineas = $lineaModel->all();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $activo = isset($_POST['activo']) ? 1 : 0;
        
        $data = [
            'codigo' => strtoupper(trim($_POST['codigo'] ?? '')),
            'nombre' => strtoupper(trim($_POST['nombre'] ?? '')),
            'descripcion' => strtoupper(trim($_POST['descripcion'] ?? '')),
            'categoria_id' => !empty($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : null,
            'linea_id' => !empty($_POST['linea_id']) ? (int)$_POST['linea_id'] : null,
            'unidad_medida' => $_POST['unidad_medida'] ?? 'UNIDAD',
            'precio' => floatval($_POST['precio'] ?? 0),
            'stock' => intval($_POST['stock'] ?? 0),
            'stock_minimo' => intval($_POST['stock_minimo'] ?? 0),
            'ubicacion' => strtoupper(trim($_POST['ubicacion'] ?? '')),
            'activo' => $activo,
            'moneda' => $_POST['moneda'] ?? 'PEN'
        ];
        
        if ($producto->update($id, $data)) {
            setFlash('success', 'Producto actualizado exitosamente');
            redirect('?url=productos');
        } else {
            setFlash('danger', 'Error al actualizar producto');
        }
    }
    
    $producto = $productoData;
    require_once __DIR__ . '/../views/productos/editar.php';
}

function eliminar($id) {
    verificarSesion();
    $producto = new Producto();
    if ($producto->cambiarEstado($id, 0)) {
        setFlash('success', 'Producto desactivado exitosamente');
    } else {
        setFlash('danger', 'Error al desactivar producto');
    }
    redirect('?url=productos');
}

function reactivar($id) {
    verificarSesion();
    $producto = new Producto();
    if ($producto->cambiarEstado($id, 1)) {
        setFlash('success', 'Producto reactivado correctamente');
    } else {
        setFlash('danger', 'Error al reactivar producto');
    }
    redirect('?url=productos');
}

function obtener($id) {
    verificarSesion();
    $producto = new Producto();
    $data = $producto->find($id);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function generarCodigo() {
    verificarSesion();
    $producto = new Producto();
    $codigo = $producto->generarCodigo();
    header('Content-Type: application/json');
    echo json_encode(['codigo' => $codigo]);
    exit;
}
?>
