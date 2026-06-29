<?php

function index() {
    verificarRol(['super_admin', 'admin_ventas', 'admin_logistica']);
    $usuario = new Usuario();
    
    $estado = $_GET['estado'] ?? 'activos';
    if ($estado === 'inactivos') {
        $usuarios = $usuario->allInactivos();
    } else {
        $usuarios = $usuario->all();
    }
    
    require_once __DIR__ . '/../views/auth/usuarios.php';
}

function crear() {
    verificarRol(['super_admin', 'admin_ventas', 'admin_logistica']);
    
    $usuarioModel = new Usuario();
    $areas = $usuarioModel->getAreas();
    $roles = $usuarioModel->getRoles();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario = new Usuario();
        $data = [
            'nombre' => $_POST['nombre'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'rol_id' => (int)$_POST['rol_id'],
            'area_id' => $_POST['area_id'] ? (int)$_POST['area_id'] : null,
            'telefono' => $_POST['telefono'] ?? '',
            'dni' => $_POST['dni'] ?? '',
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];
        
        if ($usuario->create($data)) {
            setFlash('success', 'Usuario creado exitosamente');
            redirect('?url=usuarios');
        } else {
            setFlash('danger', 'Error al crear usuario');
        }
    }
    
    require_once __DIR__ . '/../views/auth/usuarios_crear.php';
}

function editar($id) {
    verificarRol(['super_admin', 'admin_ventas', 'admin_logistica']);
    
    $usuarioModel = new Usuario();
    $user = $usuarioModel->find($id);
    $areas = $usuarioModel->getAreas();
    $roles = $usuarioModel->getRoles();
    
    if (($user['rol_nombre'] ?? '') === 'super_admin' && !esSuperAdmin()) {
        setFlash('danger', 'No tienes permiso para editar este usuario');
        redirect('?url=usuarios');
        return;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nombre' => $_POST['nombre'],
            'email' => $_POST['email'],
            'rol_id' => (int)$_POST['rol_id'],
            'area_id' => $_POST['area_id'] ? (int)$_POST['area_id'] : null,
            'telefono' => $_POST['telefono'] ?? '',
            'dni' => $_POST['dni'] ?? '',
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];
        
        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        
        if ($usuarioModel->update($id, $data)) {
            setFlash('success', 'Usuario actualizado');
            redirect('?url=usuarios');
        } else {
            setFlash('danger', 'Error al actualizar usuario');
        }
    }
    
    require_once __DIR__ . '/../views/auth/usuarios_editar.php';
}

function eliminar($id) {
    verificarRol(['super_admin']);
    
    if ($id == $_SESSION['usuario']['id']) {
        setFlash('danger', 'No puedes eliminar tu propio usuario');
        redirect('?url=usuarios');
        return;
    }
    
    $usuario = new Usuario();
    $usuario->eliminar($id);
    setFlash('success', 'Usuario desactivado');
    redirect('?url=usuarios');
}

function reactivar($id) {
    verificarRol(['super_admin', 'admin_ventas', 'admin_logistica']);
    
    $usuario = new Usuario();
    if ($usuario->reactivar($id)) {
        setFlash('success', 'Usuario reactivado correctamente');
    } else {
        setFlash('danger', 'Error al reactivar usuario');
    }
    redirect('?url=usuarios');
}

function clientes($id) {
    verificarRol(['super_admin', 'admin_ventas', 'vendedor']);
    
    $usuarioModel = new Usuario();
    $user = $usuarioModel->find($id);
    
    $roles_ventas = ['super_admin', 'admin_ventas', 'vendedor'];
    if (!in_array($user['rol_nombre'] ?? '', $roles_ventas)) {
        setFlash('warning', 'Este usuario no puede tener clientes asignados');
        redirect('?url=usuarios');
        return;
    }
    
    $clientesAsignados = $usuarioModel->getClientes($id);
    $clientesDisponibles = $usuarioModel->getClientesDisponibles($id);
    
    require_once __DIR__ . '/../views/auth/usuarios_clientes.php';
}

function asignarCliente($id) {
    verificarRol(['super_admin', 'admin_ventas', 'vendedor']);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('?url=usuarios/clientes/' . $id);
        return;
    }
    
    $usuario = new Usuario();
    $cliente_id = (int)$_POST['cliente_id'];
    
    if ($usuario->asignarCliente($id, $cliente_id)) {
        setFlash('success', 'Cliente asignado exitosamente');
    } else {
        setFlash('danger', 'Error al asignar cliente o ya está asignado');
    }
    redirect('?url=usuarios/clientes/' . $id);
}

function eliminarCliente($usuario_id, $cliente_id) {
    verificarRol(['super_admin', 'admin_ventas', 'vendedor']);
    
    if (empty($cliente_id)) {
        setFlash('danger', 'Faltan parámetros para eliminar el cliente');
        redirect('?url=usuarios/clientes/' . $usuario_id);
        return;
    }
    
    $usuario = new Usuario();
    if ($usuario->eliminarCliente((int)$usuario_id, (int)$cliente_id)) {
        setFlash('success', 'Cliente removido exitosamente');
    } else {
        setFlash('danger', 'Error al remover cliente');
    }
    redirect('?url=usuarios/clientes/' . $usuario_id);
}
?>
