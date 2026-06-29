<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nota de Pedido <?= $data['pedido']['numero_pedido'] ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #1a202c;
            padding: 30px;
            background: #fff;
        }
        .pdf-container { width: 100%; }
        .w-100 { width: 100%; }
        .table-layout { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        
        .logo {
            font-size: 40px;
            font-weight: 900;
            color: <?= $data['empresa']['color_primario'] ?? '#2563eb' ?>;
            letter-spacing: 0.9px;
        }
         .logo img { max-height: 95x; max-width: 250px; }
        .logo small {
            font-size: 11px;
            font-weight: 900;
            color: <?= $data['empresa']['color_secundario'] ?? '#64748b' ?>;
            display: block;
        }
     
	.doc-title-box { text-align: right; }
        .doc-title-box h1 { font-size: 18px; font-weight: 700; color: <?= $data['empresa']['color_secundario'] ?? '#0f172a' ?>; margin-bottom: 2px; }
        .doc-title-box .subtitle { font-size: 14px; color: <?= $data['empresa']['color_primario'] ?? '#2563eb' ?>; font-weight: 600; }
        .fecha-emision { font-size: 10px; color: #64748b; margin-top: 5px; }

        .header-line { border-bottom: 4px solid <?= $data['empresa']['color_primario'] ?? '#2563eb' ?>; margin-bottom: 15px; }
        
        .status-container { background: #f8fafc; padding: 10px 15px; border-radius: 6px; border: 1px solid #e2e8f0; margin-bottom: 20px; border-left: 4px solid <?= $data['empresa']['color_primario'] ?? '#2563eb' ?>; }
        .status-table td { font-size: 11px; padding: 2px 10px; }
        .status-label { font-weight: 600; color: #64748b; }
        .status-value { font-weight: 700; color: #0f172a; }
        .estado-pendiente { color: #d97706 !important; }
        .estado-atendido { color: #16a34a !important; }
        .estado-anulado { color: #dc2626 !important; }
        
        .section-title {
            font-size: 12px;
            font-weight: 700;
            color: <?= $data['empresa']['color_secundario'] ?? '#0f172a' ?>;
            background: #f8fafc;
            padding: 6px 10px;
            border-left: 3px solid <?= $data['empresa']['color_primario'] ?? '#2563eb' ?>;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 10px;
        }
        
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 5px; vertical-align: top; border-bottom: 1px dashed #f1f5f9; }
        .info-label { font-weight: 600; color: #64748b; width: 15%; }
        .info-value { color: #0f172a; font-weight: 500; }
        
        .pdf-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .pdf-table th { background: <?= $data['empresa']['color_primario'] ?? '#2563eb' ?>; color: #fff; padding: 8px 10px; text-align: left; font-weight: 700; font-size: 10px; text-transform: uppercase; }
        .pdf-table td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        .pdf-table tr:nth-child(even) { background: #f8fafc; }
        
        .total-row td { font-size: 14px !important; color: <?= $data['empresa']['color_primario'] ?? '#2563eb' ?>; font-weight: 700; background: #eff6ff !important; border-top: 2px solid <?= $data['empresa']['color_primario'] ?? '#2563eb' ?>; }
        
        .observacion { background: #f8fafc; padding: 10px 12px; border-radius: 4px; border-left: 3px solid <?= $data['empresa']['color_primario'] ?? '#2563eb' ?>; font-size: 10px; color: #334155; line-height: 1.5; margin-bottom: 20px; }
        
        .pdf-footer { text-align: center; font-size: 9px; border-top: 1px solid <?= $data['empresa']['color_primario'] ?? '#e2e8f0' ?>; padding-top: 15px; margin-top: 40px; }
        .pdf-footer .footer-datos { color: <?= $data['empresa']['color_secundario'] ?? '#0f172a' ?>; font-size: 9px; margin: 4px 0; }
        .pdf-footer .footer-pie { 
            font-size: 9px; 
            color: <?= $data['empresa']['color_secundario'] ?? '#64748b' ?>; 
            margin-top: 6px; 
            padding: 8px; 
            background: #f8fafc; 
            border-radius: 4px;
            border: 1px solid <?= $data['empresa']['color_primario'] ?? '#e2e8f0' ?>;
        }
        .footer-qr { margin-bottom: 8px; }
        .footer-qr img { width: 80px; height: 80px; border: 1px solid #e2e8f0; padding: 3px; background: #fff; }
        .footer-legal { font-size: 8px; color: #cbd5e1; margin-top: 4px; }
        
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
    </style>
</head>
<body>
    <div class="pdf-container">
        <table class="table-layout">
            <tr>
                <td style="width: 50%; vertical-align: middle;">
                   <div class="logo">
    <?php 
    $logo_path = '/var/www/html/pedidos/public/img/logo.png';
    
    if (file_exists($logo_path)) {
        // Convertimos la imagen a Base64 para asegurar que Dompdf la renderice sin problemas de rutas
        $logo_data = base64_encode(file_get_contents($logo_path));
        echo '<img src="data:image/png;base64,' . $logo_data . '" alt="Logo">';
    } else {
        // Fallback: si no encuentra el archivo en el servidor, muestra el texto
        echo $data['empresa']['nombre'] ?? APP_NAME;
    }
    ?>
    <small><?= $data['empresa']['nombre'] ?? APP_NAME ?> - Sistema de Pedidos</small>
</div>
                </td>
                <td style="width: 50%;" class="doc-title-box">
                    <h1>NOTA DE PEDIDO</h1>
                    <p class="subtitle"><?= $data['pedido']['numero_pedido'] ?></p>
                    <div class="fecha-emision">
                        <strong>Emisión:</strong> <?= date('d/m/Y H:i', strtotime($data['pedido']['created_at'] ?? 'now')) ?>
                    </div>
                </td>
            </tr>
        </table>
        
        <div class="header-line"></div>
        
        <div class="status-container">
            <table class="w-100 status-table">
                <tr>
                    <td><span class="status-label">Estado:</span> <span class="status-value estado-<?= strtolower($data['pedido']['estado']) ?>"><?= $data['pedido']['estado'] ?></span></td>
                    <td><span class="status-label">Condición:</span> <span class="status-value"><?= $data['pedido']['condicion'] ?></span></td>
                    <td><span class="status-label">Fecha Pedido:</span> <span class="status-value"><?= date('d/m/Y', strtotime($data['pedido']['fecha_pedido'])) ?></span></td>
                    <?php if (!empty($data['pedido']['fecha_entrega'])): ?>
                    <td><span class="status-label">Entrega:</span> <span class="status-value"><?= date('d/m/Y', strtotime($data['pedido']['fecha_entrega'])) ?></span></td>
                    <?php endif; ?>
                    <td><span class="status-label">Moneda:</span> <span class="status-value"><?= $data['pedido']['moneda'] === 'USD' ? 'Dólares ($)' : 'Soles (S/)' ?></span></td>
                </tr>
            </table>
        </div>
        
        <div class="section-title"> Datos del Cliente</div>
        <table class="info-table">
            <tr>
                <td class="info-label">Cliente:</td>
                <td class="info-value" style="width:35%;"><?= $data['cliente']['nombre'] ?></td>
                <td class="info-label">RUC/DNI:</td>
                <td class="info-value"><?= $data['cliente']['ruc_dni'] ?></td>
            </tr>
            <tr>
                <td class="info-label">Dirección:</td>
                <td class="info-value" colspan="3"><?= $data['cliente']['direccion_fiscal'] ?></td>
            </tr>
            <tr>
                <td class="info-label">Zona:</td>
                <td class="info-value"><?= $data['cliente']['zona'] ?? '-' ?></td>
                <td class="info-label">Teléfono:</td>
                <td class="info-value"><?= $data['cliente']['telefono'] ?? '-' ?></td>
            </tr>
            <?php if (!empty($data['pedido']['oc'])): ?>
            <tr>
                <td class="info-label">O/C:</td>
                <td class="info-value" colspan="3"><?= $data['pedido']['oc'] ?></td>
            </tr>
            <?php endif; ?>
        </table>
        
        <div class="section-title"> Detalle de Productos</div>
        <table class="pdf-table">
            <thead>
                <tr>
                    <th style="width:15%;">Código</th>
                    <th style="width:45%;">Producto</th>
                    <th style="width:10%; text-align: center;">Cant.</th>
                    <th style="width:15%; text-align: right;">P. Unit.</th>
                    <th style="width:15%; text-align: right;">Monto</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $simbolo = $data['pedido']['moneda'] === 'USD' ? '$' : 'S/ ';
                foreach ($data['detalles'] as $item): 
                ?>
                <tr>
                    <td style="font-weight: 600; color: <?= $data['empresa']['color_primario'] ?? '#2563eb' ?>;"><?= $item['codigo'] ?></td>
                    <td><?= $item['producto_nombre'] ?></td>
                    <td class="text-center"><?= $item['cantidad'] ?></td>
                    <td class="text-right"><?= $simbolo . number_format($item['precio_unitario'], 2) ?></td>
                    <td class="text-right"><?= $simbolo . number_format($item['monto'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" border="0"></td>
                    <td class="text-right" style="font-weight:600;">Subtotal</td>
                    <td class="text-right"><?= $simbolo . number_format($data['pedido']['subtotal'], 2) ?></td>
                </tr>
                <tr>
                    <td colspan="3" border="0"></td>
                    <td class="text-right" style="font-weight:600;">IGV (<?= $data['empresa']['igv'] ?? 18 ?>%)</td>
                    <td class="text-right"><?= $simbolo . number_format($data['pedido']['igv'], 2) ?></td>
                </tr>
                <tr class="total-row">
                    <td colspan="3"></td>
                    <td class="text-right">TOTAL</td>
                    <td class="text-right"><?= $simbolo . number_format($data['pedido']['total'], 2) ?></td>
                </tr>
            </tbody>
        </table>
        
        <?php if (!empty($data['pedido']['direccion_envio']) || !empty($data['pedido']['agencia_nombre'])): ?>
        <div class="section-title">Datos de Envío</div>
        <table class="info-table">
            <tr>
                <td class="info-label">Agencia:</td>
                <td class="info-value" style="width:35%;"><?= $data['pedido']['agencia_nombre'] ?? '-' ?></td>
                <td class="info-label">Dirección Envío:</td>
                <td class="info-value"><?= $data['pedido']['direccion_envio'] ?? '-' ?></td>
            </tr>
            <tr>
                <td class="info-label">Recibe:</td>
                <td class="info-value"><?= $data['pedido']['nombre_recibe'] ?? '-' ?></td>
                <td class="info-label">DNI:</td>
                <td class="info-value"><?= $data['pedido']['dni_recibe'] ?? '-' ?></td>
            </tr>
        </table>
        <?php endif; ?>
        
        <?php if (!empty($data['pedido']['observacion'])): ?>
        <div class="section-title">Observaciones</div>
        <div class="observacion"><?= nl2br($data['pedido']['observacion']) ?></div>
        <?php endif; ?>
        
        <div class="pdf-footer">
            <?php if (!empty($data['qr'])): ?>
            <div class="footer-qr">
                <img src="data:image/png;base64,<?= $data['qr'] ?>" alt="QR">
            </div>
            <?php endif; ?>
            <div class="footer-datos">
                <?= $data['empresa']['nombre'] ?? APP_NAME ?> - 
                RUC: <?= $data['empresa']['ruc'] ?? '20614486156' ?> - 
                Tel: <?= $data['empresa']['telefono_contacto'] ?? $data['empresa']['telefono'] ?? '2812949' ?> - 
                <?= $data['empresa']['email'] ?? 'info@bioservice.com' ?>
            </div>
            <?php if (!empty($data['empresa']['pie_pagina'])): ?>
            <div class="footer-pie"><?= $data['empresa']['pie_pagina'] ?></div>
            <?php endif; ?>
            <p>Documento generado electrónicamente - <?= date('d/m/Y H:i:s') ?></p>
            <p class="footer-legal">Este documento es una nota de pedido interna y no constituye comprobante de pago legal</p>
        </div>
    </div>
</body>
</html>
