<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
require_once 'includes/Database.php';
require_once 'models/Producto.php';

$producto = new Producto();
$productos = $producto->all();

echo "<h2>🔍 Prueba de Producto::all()</h2>";
echo "<p>Total de productos: " . count($productos) . "</p>";
echo "<pre>";
print_r($productos);
echo "</pre>";

if (empty($productos)) {
    echo "<p style='color:red;'>❌ ERROR: No hay productos en la base de datos</p>";
} else {
    echo "<p style='color:green;'>✅ Productos encontrados</p>";
}
?>
