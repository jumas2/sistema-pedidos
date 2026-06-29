<?php

// ============================================
// VERIFICAR SESIÓN
// ============================================
function verificarSesion() {
    if (!isset($_SESSION['usuario'])) {
        $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Debes iniciar sesión'];
        header('Location: ' . BASE_URL . 'login');
        exit;
    }
    
    // Verificar tiempo de inactividad
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_LIFETIME)) {
        session_destroy();
        $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Tu sesión ha expirado'];
        header('Location: ' . BASE_URL . 'login');
        exit;
    }
    $_SESSION['last_activity'] = time();
}

// ============================================
// VERIFICAR ROL
// ============================================
function verificarRol($rolesPermitidos = []) {
    verificarSesion();
    
    // Super Admin tiene acceso a todo
    $rol = $_SESSION['usuario']['rol'] ?? '';
    if ($rol === 'super_admin' || $rol === 'admin') {
        return true;
    }
    
    if (!empty($rolesPermitidos) && !in_array($rol, $rolesPermitidos)) {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'No tienes permiso para acceder'];
        header('Location: ' . BASE_URL . 'dashboard');
        exit;
    }
}

// ============================================
// VERIFICAR ROL ESPECÍFICO
// ============================================
function tieneRol($rol) {
    if (!isset($_SESSION['usuario'])) {
        return false;
    }
    $rolActual = $_SESSION['usuario']['rol'] ?? '';
    if ($rolActual === 'super_admin' || $rolActual === 'admin') {
        return true;
    }
    return $rolActual === $rol;
}

// ============================================
// VERIFICAR SUPER ADMIN
// ============================================
function esSuperAdmin() {
    $rol = $_SESSION['usuario']['rol'] ?? '';
    return $rol === 'super_admin' || $rol === 'admin';
}

// ============================================
// GENERAR CSRF TOKEN
// ============================================
function generarCsrfToken() {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

// ============================================
// VERIFICAR CSRF TOKEN
// ============================================
function verificarCsrfToken($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}
?>
