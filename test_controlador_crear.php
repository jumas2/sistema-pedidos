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

$clientes = $cliente->all();
$productos = $producto->all();
$agencias = $agencia->all();
$vendedores = $vendedor->all();
$numero_pedido = generarNumeroPedido();

echo "<h2>🔍 Datos para la vista crear pedido</h2>";
echo "<p><strong>Clientes:</strong> " . count($clientes) . "</p>";
echo "<p><strong>Productos:</strong> " . count($productos) . "</p>";
echo "<p><strong>Agencias:</strong> " . count($agencias) . "</p>";
echo "<p><strong>Vendedores:</strong> " . count($vendedores) . "</p>";
echo "<p><strong>Número de pedido:</strong> " . $numero_pedido . "</p>";

echo "<h3>Productos:</h3>";
echo "<pre>";
print_r($productos);
echo "</pre>";
?>
