<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
require_once 'includes/Database.php';
require_once 'models/Producto.php';

$producto = new Producto();
$productos = $producto->all();

echo "<h2>🔍 Productos desde el modelo</h2>";
echo "<pre>";
print_r($productos);
echo "</pre>";
echo "<p>Total: " . count($productos) . " productos</p>";
?>
