<?php
function index() {
    // Destruir la sesión
    session_destroy();
    
    // Eliminar la variable de sesión
    unset($_SESSION['usuario']);
    
    // Redirigir al login
    header('Location: ' . BASE_URL . 'login');
    exit;
}
?>
