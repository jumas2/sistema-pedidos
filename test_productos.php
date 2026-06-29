<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
require_once 'includes/Database.php';

try {
    $db = Database::getConnection();
    $stmt = $db->query("SELECT id, codigo, nombre, precio FROM productos LIMIT 5");
    $productos = $stmt->fetchAll();
    
    echo "<h2>🔍 Productos en BD</h2>";
    echo "<pre>";
    print_r($productos);
    echo "</pre>";
    echo "<p>Total: " . count($productos) . " productos</p>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
