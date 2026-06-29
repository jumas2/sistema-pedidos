<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-file-invoice"></i></span>
            Nota de Pedido: <?= $pedidoData['numero_pedido'] ?>
        </h1>
        <p class="page-subtitle">Detalle completo de la nota de pedido</p>
    </div>
    <div class="header-right">
        <div class="btn-group-modern">
            <a href="<?= BASE_URL ?>pedidos" class="btn-secondary-modern">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- BOTONES DE DESCARGA PDF -->
<!-- ========================================== -->
<div class="pdf-buttons" style="display:flex; gap:10px; margin-bottom:20px;">
    <a href="<?= BASE_URL ?>reporte/pdf/<?= $pedidoData['id'] ?>" target="_blank" class="btn btn-info" style="background:#0891b2; color:white; border:none; padding:8px 20px; border-radius:8px; text-decoration:none;">
        <i class="fas fa-file-pdf"></i> Descargar A4
    </a>
    <a href="<?= BASE_URL ?>reporte/ticket/<?= $pedidoData['id'] ?>" target="_blank" class="btn btn-secondary" style="background:#6c757d; color:white; border:none; padding:8px 20px; border-radius:8px; text-decoration:none;">
        <i class="fas fa-receipt"></i> Descargar Ticket
    </a>
</div>

<div class="pedido-detalle-container">
    <!-- ========================================== -->
    <!-- INFORMACIÓN GENERAL -->
    <!-- ========================================== -->
    <div class="detalle-section">
        <div class="detalle-section-header">
            <i class="fas fa-info-circle"></i>
            <span>Información General</span>
            <span style="margin-left: auto;">
                <span class="estado-badge <?= $pedidoData['estado'] === 'Pendiente' ? 'estado-pendiente' : ($pedidoData['estado'] === 'Anulado' ? 'estado-anulado' : 'estado-atendido') ?>">
                    <?= $pedidoData['estado'] ?>
                </span>
            </span>
        </div>
        <div class="detalle-section-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">N° Pedido:</span>
                    <span class="info-value"><strong><?= $pedidoData['numero_pedido'] ?></strong></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha Pedido:</span>
                    <span class="info-value"><?= date('d/m/Y', strtotime($pedidoData['fecha_pedido'])) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Condición:</span>
                    <span class="info-value"><?= $pedidoData['condicion'] ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Moneda:</span>
                    <span class="info-value">
                        <strong><?= $pedidoData['moneda'] === 'USD' ? '$ (Dólares)' : 'S/ (Soles)' ?></strong>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Vendedor:</span>
                    <span class="info-value"><?= $pedidoData['vendedor_nombre'] ?? 'No asignado' ?></span>
                </div>
                <?php if (!empty($pedidoData['oc'])): ?>
                <div class="info-item">
                    <span class="info-label">OC:</span>
                    <span class="info-value"><?= $pedidoData['oc'] ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($pedidoData['zona'])): ?>
                <div class="info-item">
                    <span class="info-label">Zona:</span>
                    <span class="info-value"><?= $pedidoData['zona'] ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($pedidoData['fecha_entrega'])): ?>
                <div class="info-item">
                    <span class="info-label">Fecha Entrega:</span>
                    <span class="info-value"><?= date('d/m/Y', strtotime($pedidoData['fecha_entrega'])) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- DATOS DEL CLIENTE -->
    <!-- ========================================== -->
    <div class="detalle-section">
        <div class="detalle-section-header">
            <i class="fas fa-user"></i>
            <span>Datos del Cliente</span>
        </div>
        <div class="detalle-section-body">
            <div class="info-grid">
                <div class="info-item full-width">
                    <span class="info-label">Cliente:</span>
                    <span class="info-value"><strong><?= $pedidoData['cliente_nombre'] ?></strong></span>
                </div>
                <div class="info-item">
                    <span class="info-label">RUC/DNI:</span>
                    <span class="info-value"><?= $pedidoData['ruc_dni'] ?? 'Sin RUC' ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Teléfono:</span>
                    <span class="info-value"><?= $pedidoData['telefono'] ?? 'Sin teléfono' ?></span>
                </div>
                <div class="info-item full-width">
                    <span class="info-label">Dirección:</span>
                    <span class="info-value"><?= $pedidoData['direccion_fiscal'] ?? 'Sin dirección' ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- PRODUCTOS -->
    <!-- ========================================== -->
    <div class="detalle-section">
        <div class="detalle-section-header">
            <i class="fas fa-boxes"></i>
            <span>Productos</span>
            <span style="font-size:13px; font-weight:400; color:#94a3b8; margin-left:auto;">
                <i class="fas fa-cubes"></i> <?= count($detalles) ?> items
            </span>
        </div>
        <div class="detalle-section-body">
            <div class="table-responsive">
                <table class="table-detalle-modern">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th style="text-align:center;">Cantidad</th>
                            <th style="text-align:right;">Precio Unit.</th>
                            <th style="text-align:right;">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detalles as $d): ?>
                        <tr>
                            <td><span style="background:#eef2ff; padding:2px 10px; border-radius:6px; font-weight:600; color:#4f46e5; font-size:12px;"><?= $d['codigo'] ?></span></td>
                            <td><?= $d['producto_nombre'] ?></td>
                            <td style="text-align:center;"><strong><?= $d['cantidad'] ?></strong></td>
                            <td style="text-align:right;">
                                <?= $pedidoData['moneda'] === 'USD' ? '$' : 'S/ ' ?>
                                <?= number_format($d['precio_unitario'], 2) ?>
                            </td>
                            <td style="text-align:right; font-weight:600;">
                                <?= $pedidoData['moneda'] === 'USD' ? '$' : 'S/ ' ?>
                                <?= number_format($d['monto'], 2) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align:right; font-weight:600;">Subtotal</td>
                            <td style="text-align:right; font-weight:600;">
                                <?= $pedidoData['moneda'] === 'USD' ? '$' : 'S/ ' ?>
                                <?= number_format($pedidoData['subtotal'], 2) ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:right; font-weight:600;">IGV (18%)</td>
                            <td style="text-align:right; font-weight:600;">
                                <?= $pedidoData['moneda'] === 'USD' ? '$' : 'S/ ' ?>
                                <?= number_format($pedidoData['igv'], 2) ?>
                            </td>
                        </tr>
                        <tr class="total-row">
                            <td colspan="4" style="text-align:right; font-size:18px; font-weight:700;">TOTAL</td>
                            <td style="text-align:right; font-size:18px; color:#4f46e5;">
                                <strong>
                                    <?= $pedidoData['moneda'] === 'USD' ? '$' : 'S/ ' ?>
                                    <?= number_format($pedidoData['total'], 2) ?>
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- DATOS DE ENVÍO -->
    <!-- ========================================== -->
    <?php if (!empty($pedidoData['agencia_nombre']) || !empty($pedidoData['direccion_envio']) || !empty($pedidoData['nombre_recibe'])): ?>
    <div class="detalle-section">
        <div class="detalle-section-header">
            <i class="fas fa-truck"></i>
            <span>Datos de Envío</span>
        </div>
        <div class="detalle-section-body">
            <div class="info-grid">
                <?php if (!empty($pedidoData['agencia_nombre'])): ?>
                <div class="info-item">
                    <span class="info-label">Agencia:</span>
                    <span class="info-value"><?= $pedidoData['agencia_nombre'] ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($pedidoData['direccion_envio'])): ?>
                <div class="info-item full-width">
                    <span class="info-label">Dirección:</span>
                    <span class="info-value"><?= $pedidoData['direccion_envio'] ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($pedidoData['nombre_recibe'])): ?>
                <div class="info-item">
                    <span class="info-label">Recibe:</span>
                    <span class="info-value"><?= $pedidoData['nombre_recibe'] ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($pedidoData['dni_recibe'])): ?>
                <div class="info-item">
                    <span class="info-label">DNI:</span>
                    <span class="info-value"><?= $pedidoData['dni_recibe'] ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ========================================== -->
    <!-- OBSERVACIONES -->
    <!-- ========================================== -->
    <?php if (!empty($pedidoData['observacion'])): ?>
    <div class="detalle-section">
        <div class="detalle-section-header">
            <i class="fas fa-comment-dots"></i>
            <span>Observaciones</span>
        </div>
        <div class="detalle-section-body">
            <div class="observacion-content">
                <?= nl2br(htmlspecialchars($pedidoData['observacion'])) ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ========================================== -->
    <!-- ACCIONES -->
    <!-- ========================================== -->
    <div class="detalle-actions">
        <?php if ($pedidoData['estado'] === 'Pendiente'): ?>
            <?php if (tieneRol('admin') || tieneRol('logistica')): ?>
            <form method="POST" action="<?= BASE_URL ?>pedidos/cambiarEstado/<?= $pedidoData['id'] ?>" style="display:inline;">
                <input type="hidden" name="estado" value="Atendido">
                <button type="submit" class="btn-success-modern">
                    <i class="fas fa-check"></i> Marcar Atendido
                </button>
            </form>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>pedidos/editar/<?= $pedidoData['id'] ?>" class="btn-warning-modern">
                <i class="fas fa-edit"></i> Editar
            </a>
            <?php if (tieneRol('admin') || tieneRol('logistica')): ?>
            <button type="button" class="btn-danger-modern" onclick="anularPedido(<?= $pedidoData['id'] ?>)">
                <i class="fas fa-ban"></i> Anular
            </button>
            <?php endif; ?>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>pedidos/duplicar/<?= $pedidoData['id'] ?>" class="btn-primary-modern">
            <i class="fas fa-copy"></i> Duplicar
        </a>
        <?php if (tieneRol('admin') && $pedidoData['estado'] !== 'Atendido'): ?>
        <a href="<?= BASE_URL ?>pedidos/eliminar/<?= $pedidoData['id'] ?>" class="btn-danger-modern" onclick="return confirm('¿Eliminar este pedido?')">
            <i class="fas fa-trash"></i> Eliminar
        </a>
        <?php endif; ?>
    </div>
</div>

<script>
function anularPedido(id) {
    Swal.fire({
        title: '¿Anular pedido?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= BASE_URL ?>pedidos/anular/' + id;
        }
    });
}
</script>

<style>
.pedido-detalle-container {
    background: white;
    border-radius: 16px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    padding: 24px 28px;
}
.detalle-section {
    margin-bottom: 24px;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 20px;
}
.detalle-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}
.detalle-section-header {
    font-weight: 600;
    font-size: 16px;
    color: #0f172a;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.detalle-section-header i {
    color: #4f46e5;
    font-size: 18px;
}
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px 30px;
}
.info-item {
    display: flex;
    align-items: baseline;
    padding: 4px 0;
}
.info-item.full-width {
    grid-column: span 2;
}
.info-label {
    font-weight: 600;
    color: #64748b;
    min-width: 120px;
    font-size: 13px;
}
.info-value {
    color: #0f172a;
    font-size: 14px;
}
.observacion-content {
    background: #f8fafc;
    padding: 14px 18px;
    border-radius: 8px;
    border-left: 3px solid #4f46e5;
}
.table-detalle-modern {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
.table-detalle-modern thead th {
    padding: 12px 16px;
    background: #f8fafc;
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
    color: #94a3b8;
    border-bottom: 2px solid #e2e8f0;
}
.table-detalle-modern tbody td {
    padding: 10px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}
.table-detalle-modern tfoot td {
    padding: 12px 16px;
    font-weight: 600;
    border-top: 2px solid #e2e8f0;
}
.table-detalle-modern .total-row td {
    font-size: 18px;
    color: #4f46e5;
    border-top: 3px solid #4f46e5;
    background: #f8fafc;
}
.detalle-actions {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.btn-primary-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}
.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
    color: white;
    text-decoration: none;
}
.btn-secondary-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: white;
    color: #0f172a;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}
.btn-secondary-modern:hover {
    background: #f8fafc;
    color: #0f172a;
    text-decoration: none;
}
.btn-success-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}
.btn-success-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    color: white;
    text-decoration: none;
}
.btn-danger-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}
.btn-danger-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
    color: white;
    text-decoration: none;
}
.btn-warning-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}
.btn-warning-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
    color: white;
    text-decoration: none;
}
.btn-info-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(135deg, #0891b2, #0e7490);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}
.btn-info-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(8, 145, 178, 0.4);
    color: white;
    text-decoration: none;
}
.estado-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.estado-pendiente {
    background: #fef3c7;
    color: #92400e;
}
.estado-atendido {
    background: #d1fae5;
    color: #065f46;
}
.estado-anulado {
    background: #fee2e2;
    color: #991b1b;
}
.pdf-buttons {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.pdf-buttons .btn {
    padding: 10px 24px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.pdf-buttons .btn-info {
    background: linear-gradient(135deg, #0891b2, #0e7490);
    color: white;
    border: none;
}
.pdf-buttons .btn-info:hover {
    background: linear-gradient(135deg, #0e7490, #0f5c7a);
    color: white;
}
.pdf-buttons .btn-secondary {
    background: linear-gradient(135deg, #6c757d, #495057);
    color: white;
    border: none;
}
.pdf-buttons .btn-secondary:hover {
    background: linear-gradient(135deg, #5a6268, #343a40);
    color: white;
}
@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    .info-item.full-width {
        grid-column: span 1;
    }
    .detalle-actions {
        flex-direction: column;
    }
    .detalle-actions .btn-primary-modern,
    .detalle-actions .btn-secondary-modern,
    .detalle-actions .btn-success-modern,
    .detalle-actions .btn-danger-modern,
    .detalle-actions .btn-warning-modern,
    .detalle-actions .btn-info-modern {
        justify-content: center;
        width: 100%;
    }
    .pedido-detalle-container {
        padding: 16px;
    }
    .pdf-buttons {
        flex-direction: column;
        width: 100%;
    }
    .pdf-buttons .btn {
        justify-content: center;
        width: 100%;
    }
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
