<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
require_once 'includes/Database.php';
require_once 'models/Producto.php';
require_once 'models/Cliente.php';
require_once 'models/Agencia.php';
require_once 'models/Vendedor.php';

$producto = new Producto();
$productos = $producto->all();

echo "<h2>🔍 Productos desde el modelo Producto</h2>";
echo "<p>Total: " . count($productos) . " productos</p>";
echo "<pre>";
print_r($productos);
echo "</pre>";
?>
