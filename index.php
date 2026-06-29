<?php
session_start();
require_once 'config.php';
require_once 'includes/Database.php';
require_once 'includes/funciones.php';
require_once 'includes/auth.php';

// Autoload de modelos
spl_autoload_register(function($nombre) {
    $archivo = __DIR__ . '/models/' . $nombre . '.php';
    if (file_exists($archivo)) {
        require_once $archivo;
    }
});

// Obtener URL
$url = isset($_GET['url']) ? $_GET['url'] : 'login';
$url = rtrim($url, '/');
$segments = explode('/', $url);

// Extraer controller, action y parámetros
$controller = $segments[0] ?? 'login';
$action = $segments[1] ?? 'index';
// Capturar todos los parámetros adicionales (desde la posición 2)
$params = array_slice($segments, 2);

// Rutas públicas
$publicRoutes = ['login', 'logout'];
if (!in_array($controller, $publicRoutes)) {
    verificarSesion();
}

// Mapeo de rutas
if ($controller === 'login') {
    $controller = 'auth';
    $action = 'login';
} elseif ($controller === 'logout') {
    $controller = 'auth';
    $action = 'logout';
}

// Cargar controlador
$file = "controllers/{$controller}.php";
if (file_exists($file)) {
    require_once $file;
    if (function_exists($action)) {
        // Pasar todos los parámetros a la función
        if (!empty($params)) {
            $action(...$params);
        } else {
            $action();
        }
    } else {
        echo "Error: Función '$action' no encontrada en $controller";
    }
} else {
    echo "Error: Controlador '$controller' no encontrado";
}
?>

