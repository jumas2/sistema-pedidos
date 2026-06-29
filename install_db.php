<?php
header('Content-Type: application/json');

$host = $_POST['host'] ?? 'localhost';
$user = $_POST['user'] ?? 'root';
$pass = $_POST['pass'] ?? '';
$dbname = $_POST['dbname'] ?? 'pedidos_db';
$sql = $_POST['sql'] ?? '';

if (empty($sql) || empty($pass)) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos o contraseña incorrecta']);
    exit;
}

try {
    $conn = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec($sql);
    echo json_encode(['success' => true, 'message' => 'Base de datos creada exitosamente']);
} catch (PDOException $e) {
    $msg = $e->getMessage();
    if (strpos($msg, 'Access denied') !== false) {
        $msg = 'Contraseña incorrecta. Verifica tus credenciales.';
    } elseif (strpos($msg, "Can't connect") !== false) {
        $msg = 'No se pudo conectar a MySQL. Verifica que el servicio esté corriendo.';
    }
    echo json_encode(['success' => false, 'message' => $msg]);
}
?>
