<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-file-invoice"></i></span>
            Guía de Remisión: <?= $guia['numero_guia'] ?>
        </h1>
        <p class="page-subtitle">Detalle y seguimiento de la guía</p>
    </div>
    <div class="header-right">
        <a href="<?= BASE_URL ?>logistica" class="btn-secondary-modern">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <!-- Información de la Guía -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <i class="fas fa-info-circle"></i> Información de la Guía
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>N° Guía</th>
                        <td><strong><?= $guia['numero_guia'] ?></strong></td>
                    </tr>
                    <tr>
                        <th>Pedido</th>
                        <td><a href="<?= BASE_URL ?>pedidos/ver/<?= $guia['pedido_id'] ?>"><?= $guia['numero_pedido'] ?? 'Sin pedido' ?></a></td>
                    </tr>
                    <tr>
                        <th>Cliente</th>
                        <td><?= $guia['cliente_nombre'] ?? 'Sin cliente' ?></td>
                    </tr>
                    <tr>
                        <th>Transportista</th>
                        <td>
                            <?php if (!empty($guia['transportista_nombre'])): ?>
                                <?= $guia['transportista_nombre'] . ' ' . $guia['transportista_apellido'] ?>
                            <?php else: ?>
                                <span class="text-muted">Sin asignar</span>
                                <?php if (tieneRol('admin') || tieneRol('admin_logistica')): ?>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAsignarTransportista">
                                        <i class="fas fa-user-plus"></i> Asignar
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Fecha Emisión</th>
                        <td><?= date('d/m/Y', strtotime($guia['fecha_emision'])) ?></td>
                    </tr>
                    <tr>
                        <th>Fecha Entrega</th>
                        <td><?= $guia['fecha_entrega'] ? date('d/m/Y', strtotime($guia['fecha_entrega'])) : 'Pendiente' ?></td>
                    </tr>
                    <tr>
                        <th>Dirección Entrega</th>
                        <td><?= $guia['direccion_entrega'] ?? 'No especificada' ?></td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td>
                            <span class="badge <?= $guia['estado'] === 'Pendiente' ? 'bg-warning' : ($guia['estado'] === 'En Proceso' ? 'bg-info' : ($guia['estado'] === 'Entregado' ? 'bg-success' : ($guia['estado'] === 'Cancelado' ? 'bg-danger' : 'bg-secondary'))) ?>">
                                <?= $guia['estado'] ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Observaciones</th>
                        <td><?= $guia['observacion'] ?? 'Sin observaciones' ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Seguimiento -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <i class="fas fa-history"></i> Seguimiento
                <?php if (tieneRol('admin') || tieneRol('admin_logistica')): ?>
                    <button class="btn btn-sm btn-light float-end" data-bs-toggle="modal" data-bs-target="#modalActualizarEstado">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                <?php endif; ?>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                <?php if (!empty($seguimiento)): ?>
                    <?php foreach ($seguimiento as $s): ?>
                        <div class="timeline-item">
                            <div class="timeline-badge <?= $s['estado'] === 'Entregado' ? 'bg-success' : ($s['estado'] === 'Cancelado' ? 'bg-danger' : 'bg-info') ?>"></div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <span class="badge <?= $s['estado'] === 'Entregado' ? 'bg-success' : ($s['estado'] === 'Cancelado' ? 'bg-danger' : 'bg-info') ?>">
                                        <?= $s['estado'] ?>
                                    </span>
                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($s['created_at'])) ?></small>
                                </div>
                                <?php if (!empty($s['ubicacion'])): ?>
                                    <div><i class="fas fa-map-marker-alt"></i> <?= $s['ubicacion'] ?></div>
                                <?php endif; ?>
                                <?php if (!empty($s['observacion'])): ?>
                                    <div><i class="fas fa-comment"></i> <?= $s['observacion'] ?></div>
                                <?php endif; ?>
                                <div class="text-muted small">Registrado por: <?= $s['usuario_nombre'] ?? 'Sistema' ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center">No hay seguimiento registrado</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: ASIGNAR TRANSPORTISTA -->
<!-- ========================================== -->
<div class="modal fade" id="modalAsignarTransportista" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= BASE_URL ?>?url=logistica/asignarTransportista">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-user-plus"></i> Asignar Transportista</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="guia_id" value="<?= $guia['id'] ?>">
                    <div class="form-group">
                        <label>Transportista *</label>
                        <select name="transportista_id" class="form-control" required>
                            <option value="">Seleccionar transportista</option>
                            <?php foreach ($transportistas as $t): ?>
                                <option value="<?= $t['id'] ?>"><?= $t['nombre'] . ' ' . $t['apellido'] ?> - <?= $t['dni'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Asignar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: ACTUALIZAR ESTADO -->
<!-- ========================================== -->
<div class="modal fade" id="modalActualizarEstado" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= BASE_URL ?>?url=logistica/actualizarEstado/<?= $guia['id'] ?>">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Actualizar Estado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Estado *</label>
                        <select name="estado" class="form-control" required>
                            <option value="Pendiente" <?= $guia['estado'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="En Proceso" <?= $guia['estado'] === 'En Proceso' ? 'selected' : '' ?>>En Proceso</option>
                            <option value="Entregado" <?= $guia['estado'] === 'Entregado' ? 'selected' : '' ?>>Entregado</option>
                            <option value="Cancelado" <?= $guia['estado'] === 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
                            <option value="Cambio Transporte" <?= $guia['estado'] === 'Cambio Transporte' ? 'selected' : '' ?>>Cambio Transporte</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ubicación</label>
                        <input type="text" name="ubicacion" class="form-control" placeholder="Ej: En tránsito, Bodega, etc.">
                    </div>
                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea name="observacion" class="form-control" rows="2" placeholder="Detalles del estado actual"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.timeline-item {
    position: relative;
    padding-left: 30px;
    margin-bottom: 15px;
}
.timeline-badge {
    position: absolute;
    left: 0;
    top: 5px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid #e2e8f0;
}
.timeline-content {
    padding-left: 10px;
    border-left: 2px solid #e2e8f0;
    padding-bottom: 10px;
}
.timeline-item:last-child .timeline-content {
    border-left: none;
}
.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.form-group { margin-bottom: 15px; }
.form-group label { font-weight: 600; font-size: 13px; color: #0f172a; display: block; margin-bottom: 4px; }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
