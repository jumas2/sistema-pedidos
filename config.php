<?php
// ============================================
// CONFIGURACIÓN DE LA APLICACIÓN
// ============================================

// Base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'pedidos_db');
define('DB_USER', 'pedidos_user');
define('DB_PASS', 'Pedidos2026!');

// URL
define('BASE_URL', '/pedidos/');
define('APP_NAME', 'Sistema de Notas de Pedidos');
define('IGV', 0.18);

// Zona horaria
date_default_timezone_set('America/Lima');

// ============================================
// SEGURIDAD DE SESIONES
// ============================================
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 en HTTPS
ini_set('session.cookie_samesite', 'Lax');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================
// REPORTE DE ERRORES
// ============================================
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// ============================================
// CONSTANTES DE SEGURIDAD
// ============================================
define('CSRF_TOKEN_NAME', 'csrf_token');
define('SESSION_LIFETIME', 3600); // 1 hora
?>
