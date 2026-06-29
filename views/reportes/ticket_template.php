<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket <?= $data['pedido']['numero_pedido'] ?></title>
    <style>
        @page { margin: 0px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 10pt;
            background: #fff;
            color: #000;
            padding: 4px 6px 4px 4px;
            width: 100%;
        }
        .ticket-container { 
            width: 100%; 
            max-width: 215pt;
        }
        .w-100 { width: 100%; }
        
        .ticket-header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        .ticket-header .store-name { 
            font-size: 12pt; 
            font-weight: bold; 
            letter-spacing: 0.5px; 
            margin-bottom: 2px; 
        }
        .ticket-header .store-info { 
            font-size: 9.5pt; 
            line-height: 1.2; 
        }
        .ticket-header .doc-title { 
            font-size: 11.5pt; 
            font-weight: bold; 
            margin-top: 5px; 
        }
        .ticket-header .doc-number { 
            font-size: 11.5pt; 
            font-weight: bold; 
        }
        
        .ticket-status-box { 
            background: #f2f2f2; 
            padding: 5px; 
            font-size: 9.5pt; 
            font-weight: bold; 
            margin-bottom: 8px; 
            border: 1px solid #000; 
        }
        
        .info-box { 
            border-bottom: 2px dashed #000; 
            padding-bottom: 6px; 
            margin-bottom: 8px; 
        }
        .info-box td { 
            font-size: 9.5pt; 
            padding: 2px 0; 
            vertical-align: top; 
        }
        
        .ticket-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 8px; 
            table-layout: fixed; 
        }
        .ticket-table th { 
            border-bottom: 2px solid #000; 
            padding: 4px 0; 
            font-size: 9.5pt; 
            font-weight: bold; 
        }
        
        .prod-title-row td { 
            font-weight: bold; 
            font-size: 10pt; 
            padding-top: 6px; 
            padding-bottom: 1px;
            line-height: 1.2;
            word-wrap: break-word;
        }
        .prod-values-row td { 
            font-size: 9.5pt; 
            border-bottom: 1px dotted #000; 
            padding-bottom: 6px; 
        }
        
        .totales-box { 
            width: 100%; 
            border-collapse: collapse; 
            border-top: 2px dashed #000; 
            padding-top: 4px; 
            margin-top: 4px; 
        }
        .totales-box td { 
            font-size: 10pt; 
            padding: 3px 0; 
        }
        .total-line td { 
            font-size: 12pt !important; 
            font-weight: bold; 
            border-top: 2px solid #000; 
            padding-top: 4px; 
        }
        
        .alert-box { 
            border-top: 2px dashed #000; 
            padding: 6px 0; 
            font-size: 9pt; 
            line-height: 1.3; 
            word-wrap: break-word; 
        }
        
        .ticket-footer { 
            text-align: center; 
            border-top: 2px dashed #000; 
            padding-top: 10px; 
            margin-top: 12px; 
            font-size: 9pt; 
        }
        .ticket-footer .qr { margin-bottom: 6px; }
        .ticket-footer .qr img { 
            width: 85px; 
            height: 85px; 
            border: 1px solid #000; 
            padding: 2px; 
        }
        .ticket-footer .footer-pie {
            font-size: 8pt;
            color: #555;
            margin-top: 4px;
        }
        
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="ticket-header">
            <div class="store-name"><?= $data['empresa']['nombre'] ?? APP_NAME ?></div>
            <div class="store-info">RUC: <?= $data['empresa']['ruc'] ?? '20614486156' ?></div>
            <div class="store-info">Tel: <?= $data['empresa']['telefono_contacto'] ?? $data['empresa']['telefono'] ?? '2812949' ?></div>
            <div class="doc-title">NOTA DE PEDIDO</div>
            <div class="doc-number"><?= $data['pedido']['numero_pedido'] ?></div>
            <div class="store-info" style="margin-top: 2px;"><?= date('d/m/Y H:i', strtotime($data['pedido']['created_at'] ?? 'now')) ?></div>
        </div>
        
        <div class="ticket-status-box">
            <table class="w-100" style="border-collapse: collapse;">
                <tr>
                    <td style="width: 50%;">EST: <?= $data['pedido']['estado'] ?></td>
                    <td class="text-right" style="width: 50%;">COND: <?= $data['pedido']['condicion'] ?></td>
                </tr>
            </table>
        </div>
        
        <div class="info-box">
            <table class="w-100" style="border-collapse: collapse;">
                <tr>
                    <td style="font-weight: bold; width: 25%;">Cli:</td>
                    <td style="width: 75%; word-wrap: break-word;"><?= $data['cliente']['nombre'] ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">RUC/DNI:</td>
                    <td><?= $data['cliente']['ruc_dni'] ?></td>
                </tr>
                <?php if (!empty($data['cliente']['direccion_fiscal'])): ?>
                <tr>
                    <td style="font-weight: bold;">Dir:</td>
                    <td style="font-size: 9pt; word-wrap: break-word;"><?= $data['cliente']['direccion_fiscal'] ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($data['pedido']['oc'])): ?>
                <tr>
                    <td style="font-weight: bold;">O/C:</td>
                    <td><?= $data['pedido']['oc'] ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
        
        <table class="ticket-table">
            <thead>
                <tr>
                    <th style="text-align: left; width: 45%;">Descripción</th>
                    <th class="text-center" style="width: 20%;">Cant.</th>
                    <th class="text-right" style="width: 35%;">Monto</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $simbolo = $data['pedido']['moneda'] === 'USD' ? '$ ' : 'S/ ';
                foreach ($data['detalles'] as $item): 
                ?>
                <tr class="prod-title-row">
                    <td colspan="3">[<?= $item['codigo'] ?>] <?= $item['producto_nombre'] ?></td>
                </tr>
                <tr class="prod-values-row">
                    <td style="color: #333; width: 45%;">Pre:<?= number_format($item['precio_unitario'], 2) ?></td>
                    <td class="text-center" style="width: 20%;">x<?= $item['cantidad'] ?></td>
                    <td class="text-right" style="width: 35%;"><?= $simbolo . number_format($item['monto'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <table class="totales-box">
            <tr>
                <td style="width: 50%;">Subtotal</td>
                <td class="text-right" style="width: 50%;"><?= $simbolo . number_format($data['pedido']['subtotal'], 2) ?></td>
            </tr>
            <tr>
                <td>IGV (<?= $data['empresa']['igv'] ?? 18 ?>%)</td>
                <td class="text-right"><?= $simbolo . number_format($data['pedido']['igv'], 2) ?></td>
            </tr>
            <tr class="total-line">
                <td>TOTAL</td>
                <td class="text-right"><?= $simbolo . number_format($data['pedido']['total'], 2) ?></td>
            </tr>
        </table>
        
        <?php if (!empty($data['pedido']['direccion_envio']) || !empty($data['pedido']['agencia_nombre'])): ?>
        <div class="alert-box">
            <strong>[DATOS DE DESPACHO]</strong><br>
            Agencia: <?= $data['pedido']['agencia_nombre'] ?? '-' ?><br>
            Dirección: <?= $data['pedido']['direccion_envio'] ?? '-' ?><br>
            Recibe: <?= $data['pedido']['nombre_recibe'] ?? '-' ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($data['pedido']['observacion'])): ?>
        <div class="alert-box">
            <strong>[OBSERVACIONES]</strong><br>
            <?= nl2br($data['pedido']['observacion']) ?>
        </div>
        <?php endif; ?>
        
        <div class="ticket-footer">
            <?php if (!empty($data['qr'])): ?>
            <div class="qr">
                <img src="data:image/png;base64,<?= $data['qr'] ?>" alt="QR">
            </div>
            <?php endif; ?>
            <?php if (!empty($data['empresa']['pie_pagina'])): ?>
            <div class="footer-pie"><?= $data['empresa']['pie_pagina'] ?></div>
            <?php endif; ?>
            <p style="font-weight: bold;">¡Gracias por su compra!</p>
            <p style="margin-top: 4px; font-size: 8.5pt; color: #333;"><?= date('d/m/Y H:i:s') ?></p>
        </div>
    </div>
</body>
</html>
