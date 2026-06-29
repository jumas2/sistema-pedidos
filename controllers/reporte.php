<?php
// ============================================
// REPORTES: A4 Y TICKET CON DATOS DE EMPRESA
// ============================================

function pdf($id) {
    verificarSesion();
    $data = obtenerDatosPedido($id);
    $data['empresa'] = obtenerDatosEmpresa();
    $html = renderizarPDF($data, 'a4');
    generarPDFConDompdf($html, $data['pedido']['numero_pedido'], 'A4');
}

function ticket($id) {
    verificarSesion();
    $data = obtenerDatosPedido($id);
    $data['empresa'] = obtenerDatosEmpresa();
    $html = renderizarPDF($data, 'ticket');
    generarPDFConDompdf($html, $data['pedido']['numero_pedido'] . '_ticket', [0, 0, 226, 680]);
}

function obtenerDatosEmpresa() {
    $empresa = new Empresa();
    return $empresa->get();
}

function obtenerDatosPedido($id) {
    $pedido = new Pedido();
    $pedidoData = $pedido->find($id);
    $detalles = $pedido->getDetalles($id);
    $cliente = new Cliente();
    $clienteData = $cliente->find($pedidoData['cliente_id']);
    
    $qrBase64 = generarQR($pedidoData);
    
    return [
        'pedido' => $pedidoData,
        'detalles' => $detalles,
        'cliente' => $clienteData,
        'qr' => $qrBase64
    ];
}

function generarQR($pedido) {
    $data = json_encode([
        'numero' => $pedido['numero_pedido'],
        'cliente' => $pedido['cliente_nombre'] ?? '',
        'fecha' => $pedido['fecha_pedido'],
        'total' => $pedido['total']
    ]);
    
    $url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($data);
    $qrImage = @file_get_contents($url);
    
    if ($qrImage) {
        return base64_encode($qrImage);
    }
    return '';
}

function renderizarPDF($data, $formato) {
    ob_start();
    if ($formato === 'ticket') {
        require_once __DIR__ . '/../views/reportes/ticket_template.php';
    } else {
        require_once __DIR__ . '/../views/reportes/pdf_template.php';
    }
    return ob_get_clean();
}

function generarPDFConDompdf($html, $nombre, $tamano = 'A4') {
    if (!class_exists('Dompdf\Dompdf')) {
        $paths = [
            __DIR__ . '/../vendor/autoload.php',
            __DIR__ . '/../../vendor/autoload.php',
        ];
        foreach ($paths as $path) {
            if (file_exists($path)) {
                require_once $path;
                break;
            }
        }
    }
    
    if (!class_exists('Dompdf\Dompdf')) {
        die("❌ Dompdf no está instalado. Ejecuta: composer require dompdf/dompdf");
    }
    
    $options = new \Dompdf\Options();
    $options->set('defaultFont', 'Helvetica');
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    
    $dompdf = new \Dompdf\Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper($tamano, 'portrait');
    
    $dompdf->render();
    $dompdf->stream("Nota_Pedido_$nombre.pdf", ['Attachment' => false]);
    exit;
}
?>
