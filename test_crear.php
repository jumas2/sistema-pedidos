<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
require_once 'includes/Database.php';
require_once 'includes/funciones.php';
require_once 'includes/auth.php';
require_once 'models/Producto.php';
require_once 'models/Cliente.php';
require_once 'models/Agencia.php';
require_once 'models/Vendedor.php';

// Simular sesión
if (!isset($_SESSION)) {
    session_start();
}
$_SESSION['usuario'] = ['id' => 1, 'nombre' => 'Admin', 'rol' => 'admin'];

$cliente = new Cliente();
$producto = new Producto();
$agencia = new Agencia();
$vendedor = new Vendedor();

$data = [
    'clientes' => $cliente->all(),
    'productos' => $producto->all(),
    'agencias' => $agencia->all(),
    'vendedores' => $vendedor->all(),
    'numero_pedido' => 'NP-2026-0001'
];

echo "<h2>🔍 Datos para la vista</h2>";
echo "<p>Productos: " . count($data['productos']) . "</p>";
echo "<pre>";
print_r($data['productos']);
echo "</pre>";
?>
