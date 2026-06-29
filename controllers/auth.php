<?php

function login() {
    // Si ya está logueado, redirigir
    if (isset($_SESSION['usuario'])) {
        redirect('dashboard');
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Validar campos
        if (empty($email) || empty($password)) {
            setFlash('danger', 'Por favor, completa todos los campos');
        } else {
            $usuario = new Usuario();
            $user = $usuario->autenticar($email, $password);
            
            if ($user) {
                // Crear sesión segura
                session_regenerate_id(true);
                $_SESSION['usuario'] = [
                    'id' => $user['id'],
                    'nombre' => $user['nombre'],
                    'email' => $user['email'],
                    'rol' => $user['rol_nombre'] ?? 'vendedor',
                    'rol_id' => $user['rol_id'],
                    'area_id' => $user['area_id']
                ];
                $_SESSION['last_activity'] = time();
                
                redirect('dashboard');
            } else {
                setFlash('danger', 'Email o contraseña incorrectos');
            }
        }
    }
    
    require_once __DIR__ . '/../views/auth/login.php';
}

function logout() {
    // Destruir sesión completamente
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
    redirect('login');
}
?>
