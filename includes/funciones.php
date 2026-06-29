<?php
function redirect($url) {
    // Si la URL ya contiene '?url=', usarla directamente
    if (strpos($url, '?url=') === 0) {
        header('Location: ' . BASE_URL . $url);
    } else {
        header('Location: ' . BASE_URL . '?url=' . $url);
    }
    exit;
}

function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function generarNumeroPedido() {
    $anio = date('Y');
    $numero = rand(1000, 9999);
    return 'NP-' . $anio . '-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
}
