<?php

function index() {
    verificarSesion();
    $cliente = new Cliente();
    $clientes = $cliente->all();
    require_once __DIR__ . "/../views/clientes/index.php";
}

function crear() {
    verificarSesion();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cliente = new Cliente();
        $data = [
            'nombre' => $_POST['nombre'],
            'ruc_dni' => $_POST['ruc_dni'],
            'direccion_fiscal' => $_POST['direccion_fiscal'],
            'zona' => $_POST['zona'],
            'telefono' => $_POST['telefono'],
            'email' => $_POST['email']
        ];
        if ($cliente->create($data)) {
            setFlash('success', 'Cliente registrado exitosamente');
            redirect('?url=clientes');
        }
    }
    require_once __DIR__ . '/../views/clientes/crear.php';
}

function editar($id) {
    verificarSesion();
    $cliente = new Cliente();
    $clienteData = $cliente->find($id);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nombre' => $_POST['nombre'],
            'ruc_dni' => $_POST['ruc_dni'],
            'direccion_fiscal' => $_POST['direccion_fiscal'],
            'zona' => $_POST['zona'],
            'telefono' => $_POST['telefono'],
            'email' => $_POST['email']
        ];
        if ($cliente->update($id, $data)) {
            setFlash('success', 'Cliente actualizado exitosamente');
            redirect('?url=clientes');
        }
    }
    $cliente = $clienteData;
    require_once __DIR__ . '/../views/clientes/editar.php';
}

function eliminar($id) {
    verificarSesion();
    $cliente = new Cliente();
    if ($cliente->cambiarEstado($id, 0)) {
        setFlash('success', 'Cliente desactivado exitosamente');
    } else {
        setFlash('danger', 'Error al desactivar cliente');
    }
    redirect('?url=clientes');
}

function reactivar($id) {
    verificarSesion();
    $cliente = new Cliente();
    if ($cliente->cambiarEstado($id, 1)) {
        setFlash('success', 'Cliente reactivado correctamente');
    } else {
        setFlash('danger', 'Error al reactivar cliente');
    }
    redirect('?url=clientes');
}

function obtener($id) {
    verificarSesion();
    $cliente = new Cliente();
    $data = $cliente->find($id);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function importar() {
    verificarSesion();
    require_once __DIR__ . '/../views/clientes/importar.php';
}
?>
