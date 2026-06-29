<?php

function index() {
    verificarSesion();
    verificarRol(['super_admin', 'admin']);
    
    $empresa = new Empresa();
    $data = $empresa->get();
    
    require_once __DIR__ . '/../views/empresa/index.php';
}

function actualizar() {
    verificarSesion();
    verificarRol(['super_admin', 'admin']);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('empresa');
        return;
    }
    
    $data = [
        'nombre' => strtoupper(trim($_POST['nombre'] ?? '')),
        'ruc' => trim($_POST['ruc'] ?? ''),
        'direccion' => strtoupper(trim($_POST['direccion'] ?? '')),
        'telefono' => trim($_POST['telefono'] ?? ''),
        'telefono_contacto' => trim($_POST['telefono_contacto'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'moneda' => $_POST['moneda'] ?? 'PEN',
        'igv' => floatval($_POST['igv'] ?? 18.00),
        'color_primario' => $_POST['color_primario'] ?? '#4f46e5',
        'color_secundario' => $_POST['color_secundario'] ?? '#0f172a',
        'color_acento' => $_POST['color_acento'] ?? '#e2e8f0',
        'pie_pagina' => trim($_POST['pie_pagina'] ?? '')
    ];
    
    $empresa = new Empresa();
    if ($empresa->update($data)) {
        setFlash('success', 'Datos de la empresa actualizados correctamente');
    } else {
        setFlash('danger', 'Error al actualizar los datos');
    }
    redirect('empresa');
}

function subirLogo() {
    verificarSesion();
    verificarRol(['super_admin', 'admin']);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('empresa');
        return;
    }
    
    if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
        setFlash('danger', 'Error al subir el logo');
        redirect('empresa');
        return;
    }
    
    $archivo = $_FILES['logo'];
    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $extensiones_validas = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
    
    if (!in_array(strtolower($extension), $extensiones_validas)) {
        setFlash('danger', 'Formato de imagen no válido. Use JPG, PNG, GIF, SVG o WEBP');
        redirect('empresa');
        return;
    }
    
    $nombre_archivo = 'logo.' . $extension;
    $ruta_destino = __DIR__ . '/../public/img/' . $nombre_archivo;
    
    if (!is_dir(__DIR__ . '/../public/img')) {
        mkdir(__DIR__ . '/../public/img', 0755, true);
    }
    
    if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
        $empresa = new Empresa();
        $empresa->updateLogo('public/img/' . $nombre_archivo);
        setFlash('success', 'Logo actualizado correctamente');
    } else {
        setFlash('danger', 'Error al guardar el logo');
    }
    redirect('empresa');
}
?>
