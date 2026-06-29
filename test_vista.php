<?php
require_once 'config.php';
require_once 'includes/Database.php';
require_once 'models/Producto.php';

$producto = new Producto();
$productos = $producto->all();

echo "<h2>Productos en la vista:</h2>";
echo "<pre>";
print_r($productos);
echo "</pre>";
echo "<p>Total: " . count($productos) . "</p>";
?>
