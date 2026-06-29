<?php
$archivo = $argv[1] ?? null;

if (!$archivo) {
    echo "❌ Uso: php limpiar_bom.php /ruta/al/archivo.csv\n";
    exit(1);
}

if (!file_exists($archivo)) {
    echo "❌ El archivo no existe: $archivo\n";
    exit(1);
}

// Leer el archivo
$contenido = file_get_contents($archivo);

// 1. Eliminar BOM (caracteres invisibles al inicio)
if (strpos($contenido, "\xEF\xBB\xBF") === 0) {
    $contenido = substr($contenido, 3);
    echo "✅ BOM eliminado\n";
} else {
    echo "ℹ️ No se encontró BOM en el archivo\n";
}

// 2. Normalizar saltos de línea
$contenido = str_replace("\r\n", "\n", $contenido);
$contenido = str_replace("\r", "\n", $contenido);

// 3. Guardar archivo limpio
$nombre_original = pathinfo($archivo, PATHINFO_FILENAME);
$ruta_nueva = dirname($archivo) . '/' . $nombre_original . '_limpio.csv';
file_put_contents($ruta_nueva, $contenido);

echo "✅ Archivo limpio guardado en: $ruta_nueva\n";

// 4. Mostrar vista previa
echo "\n📄 Vista previa del archivo limpio:\n";
echo "----------------------------------------\n";
$lineas = explode("\n", $contenido);
for ($i = 0; $i < min(5, count($lineas)); $i++) {
    echo $lineas[$i] . "\n";
}
echo "----------------------------------------\n";
echo "\n🚀 Ahora importa: $ruta_nueva\n";
?>
