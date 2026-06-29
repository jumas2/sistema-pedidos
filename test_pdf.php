<?php
require_once __DIR__ . '/config.php';

// Verificar que Dompdf está instalado
if (class_exists('Dompdf\Dompdf')) {
    echo "✅ Dompdf está instalado<br>";
} else {
    echo "❌ Dompdf NO está instalado<br>";
    echo "Intentando cargar autoload...<br>";
    
    $paths = [
        __DIR__ . '/vendor/autoload.php',
        __DIR__ . '/../vendor/autoload.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            echo "✅ Encontrado: $path<br>";
            require_once $path;
            break;
        }
    }
    
    if (class_exists('Dompdf\Dompdf')) {
        echo "✅ Dompdf cargado correctamente<br>";
    } else {
        echo "❌ Dompdf no se pudo cargar<br>";
        echo "Ejecuta: composer require dompdf/dompdf<br>";
    }
}

// Probar generación simple de PDF
if (class_exists('Dompdf\Dompdf')) {
    try {
        $html = '<h1>Test PDF</h1><p>Este es un PDF de prueba</p>';
        $options = new \Dompdf\Options();
        $options->set('defaultFont', 'Helvetica');
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("test.pdf", ['Attachment' => false]);
        exit;
    } catch (Exception $e) {
        echo "❌ Error generando PDF: " . $e->getMessage();
    }
}
?>
