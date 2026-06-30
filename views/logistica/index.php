<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-truck"></i></span>
            Logística y Despachos
        </h1>
        <p class="page-subtitle">Gestión de guías de remisión y seguimiento de envíos</p>
    </div>
    <div class="header-right">
        <button type="button" class="btn-primary-modern" data-bs-toggle="modal" data-bs-target="#modalCrearGuia">
            <i class="fas fa-plus"></i> Nueva Guía
        </button>
    </div>
</div>

<!-- ========================================== -->
<!-- TARJETAS DE ESTADÍSTICAS -->
<!-- ========================================== -->
<div class="stats-grid">
    <div class="stat-card" onclick="window.location.href='<?= BASE_URL ?>?url=logistica'">
        <div class="stat-icon blue"><i class="fas fa-file-invoice"></i></div>
        <div>
            <div class="stat-number"><?= $estadisticas['total'] ?? 0 ?></div>
            <div class="stat-label">Total Guías</div>
        </div>
        <div class="stat-trend up">+<?= $estadisticas['guias_mes'] ?? 0 ?> mes</div>
    </div>

    <div class="stat-card" onclick="window.location.href='<?= BASE_URL ?>?url=logistica&estado=Pendiente'">
        <div class="stat-icon yellow"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-number"><?= $estadisticas['pendientes'] ?? 0 ?></div>
            <div class="stat-label">Pendientes</div>
        </div>
        <div class="stat-trend down">⏳</div>
    </div>

    <div class="stat-card" onclick="window.location.href='<?= BASE_URL ?>?url=logistica&estado=En%20Proceso'">
        <div class="stat-icon info"><i class="fas fa-spinner"></i></div>
        <div>
            <div class="stat-number"><?= $estadisticas['en_proceso'] ?? 0 ?></div>
            <div class="stat-label">En Proceso</div>
        </div>
        <div class="stat-trend">🚚</div>
    </div>

    <div class="stat-card" onclick="window.location.href='<?= BASE_URL ?>?url=logistica&estado=Entregado'">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="stat-number"><?= $estadisticas['entregados'] ?? 0 ?></div>
            <div class="stat-label">Entregados</div>
        </div>
        <div class="stat-trend up">+<?= $estadisticas['entregados_hoy'] ?? 0 ?> hoy</div>
    </div>

    <div class="stat-card" onclick="window.location.href='<?= BASE_URL ?>?url=logistica&estado=Cancelado'">
        <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
        <div>
            <div class="stat-number"><?= $estadisticas['cancelados'] ?? 0 ?></div>
            <div class="stat-label">Cancelados</div>
        </div>
        <div class="stat-trend down">✖</div>
    </div>

    <div class="stat-card" onclick="window.location.href='<?= BASE_URL ?>?url=logistica/transportistas'">
        <div class="stat-icon purple"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-number"><?= $estadisticas['transportistas'] ?? 0 ?></div>
            <div class="stat-label">Transportistas</div>
        </div>
        <div class="stat-trend up">Activos</div>
    </div>

    <div class="stat-card" onclick="window.location.href='<?= BASE_URL ?>?url=logistica/vehiculos'">
        <div class="stat-icon success"><i class="fas fa-car"></i></div>
        <div>
            <div class="stat-number"><?= $estadisticas['vehiculos_disponibles'] ?? 0 ?></div>
            <div class="stat-label">Vehículos</div>
        </div>
        <div class="stat-trend up"><?= $estadisticas['vehiculos_en_uso'] ?? 0 ?> uso</div>
    </div>
</div>

<!-- ========================================== -->
<!-- PEDIDOS PENDIENTES DE DESPACHO -->
<!-- ========================================== -->
<div class="card mb-4">
    <div class="card-header bg-warning text-dark">
        <i class="fas fa-clock"></i> Pedidos Pendientes de Despacho
        <span class="badge bg-dark float-end"><?= count($pedidosPendientes) ?></span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>N° Pedido</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pedidosPendientes)): ?>
                        <?php foreach ($pedidosPendientes as $p): ?>
                            <tr>
                                <td><strong><?= $p['numero_pedido'] ?></strong></td>
                                <td><?= $p['cliente_nombre'] ?></td>
                                <td><?= date('d/m/Y', strtotime($p['fecha_pedido'])) ?></td>
                                <td>S/ <?= number_format($p['total'], 2) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="crearGuiaDesdePedido(<?= $p['id'] ?>, '<?= $p['numero_pedido'] ?>')">
                                        <i class="fas fa-file-invoice"></i> Crear Guía
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay pedidos pendientes de despacho</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- GUÍAS DE REMISIÓN -->
<!-- ========================================== -->
<div class="card">
    <div class="card-header bg-info text-white">
        <i class="fas fa-file-invoice"></i> Guías de Remisión
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>N° Guía</th>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Transportista</th>
                        <th>Fecha Emisión</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($guias as $g): ?>
                        <tr>
                            <td><strong><?= $g['numero_guia'] ?></strong></td>
                            <td><?= $g['numero_pedido'] ?? '-' ?></td>
                            <td><?= $g['cliente_nombre'] ?? '-' ?></td>
                            <td><?= ($g['transportista_nombre'] ?? '') . ' ' . ($g['transportista_apellido'] ?? '') ?: 'Sin asignar' ?></td>
                            <td><?= date('d/m/Y', strtotime($g['fecha_emision'])) ?></td>
                            <td>
                                <span class="badge <?= $g['estado'] === 'Pendiente' ? 'bg-warning' : ($g['estado'] === 'En Proceso' ? 'bg-info' : ($g['estado'] === 'Entregado' ? 'bg-success' : ($g['estado'] === 'Cancelado' ? 'bg-danger' : 'bg-secondary'))) ?>">
                                    <?= $g['estado'] ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>?url=logistica/ver/<?= $g['id'] ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: CREAR GUÍA -->
<!-- ========================================== -->
<div class="modal fade" id="modalCrearGuia" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= BASE_URL ?>?url=logistica/crearGuia">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-file-invoice"></i> Nueva Guía de Remisión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>N° Guía *</label>
                        <input type="text" name="numero_guia" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Pedido *</label>
                        <select name="pedido_id" class="form-control" required>
                            <option value="">Seleccionar pedido</option>
                            <?php foreach ($pedidosPendientes as $p): ?>
                                <?php
                                    $db = Database::getConnection();
                                    $stmt = $db->prepare("SELECT id FROM guias_remision WHERE pedido_id = :pedido_id");
                                    $stmt->execute(["pedido_id" => $p["id"]]);
                                    $tiene_guia = $stmt->fetch();
                                    if (!$tiene_guia):
                                ?>
                                    <option value="<?= $p['id'] ?>"><?= $p['numero_pedido'] ?> - <?= $p['cliente_nombre'] ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Transportista</label>
                        <select name="transportista_id" class="form-control">
                            <option value="">Sin asignar</option>
                            <?php foreach ($transportistas as $t): ?>
                                <option value="<?= $t['id'] ?>"><?= $t['nombre'] . ' ' . $t['apellido'] ?> - <?= $t['dni'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fecha Emisión *</label>
                        <input type="date" name="fecha_emision" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Dirección de Entrega</label>
                        <input type="text" name="direccion_entrega" class="form-control" placeholder="Dirección donde se entregará el pedido">
                    </div>
                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea name="observacion" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Crear Guía</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var BASE_URL = '<?= BASE_URL ?>';

function crearGuiaDesdePedido(pedidoId, numeroPedido) {
    var modal = new bootstrap.Modal(document.getElementById('modalCrearGuia'));
    modal.show();
    
    var select = document.querySelector('select[name="pedido_id"]');
    for (var i = 0; i < select.options.length; i++) {
        if (select.options[i].value == pedidoId) {
            select.options[i].selected = true;
            break;
        }
    }
    
    var inputGuia = document.querySelector('input[name="numero_guia"]');
    fetch(BASE_URL + '?url=logistica/generarNumeroGuiaAjax')
        .then(response => response.json())
        .then(data => {
            inputGuia.value = data.numero_guia;
        })
        .catch(() => {
            var fecha = new Date();
            var año = fecha.getFullYear();
            var random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
            inputGuia.value = 'G-' + año + '-' + random;
        });
}

document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('buscarPedido');
    var rows = document.querySelectorAll('.fila-pedido');
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            var term = this.value.toLowerCase().trim();
            rows.forEach(function(row) {
                var text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }
});
</script>

<style>
.form-group { margin-bottom: 15px; }
.form-group label { font-weight: 600; font-size: 13px; color: #0f172a; display: block; margin-bottom: 4px; }

.stats-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
    margin-bottom: 20px;
}

.stat-card {
    background: white;
    border-radius: 10px;
    padding: 8px 10px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    position: relative;
    min-height: 56px;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.stat-card .stat-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    flex-shrink: 0;
}

.stat-card .stat-icon.blue { background: #eef2ff; color: #4f46e5; }
.stat-card .stat-icon.green { background: #ecfdf5; color: #10b981; }
.stat-card .stat-icon.yellow { background: #fffbeb; color: #f59e0b; }
.stat-card .stat-icon.purple { background: #f5f3ff; color: #8b5cf6; }
.stat-card .stat-icon.red { background: #fef2f2; color: #ef4444; }
.stat-card .stat-icon.info { background: #e0f2fe; color: #0891b2; }
.stat-card .stat-icon.success { background: #d1fae5; color: #059669; }

.stat-card .stat-number { font-size: 16px; font-weight: 800; line-height: 1.1; color: #0f172a; }
.stat-card .stat-label { font-size: 9px; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px; }
.stat-card .stat-trend { font-size: 8px; font-weight: 600; padding: 1px 6px; border-radius: 12px; margin-left: auto; white-space: nowrap; }
.stat-card .stat-trend.up { color: #065f46; background: #d1fae5; }
.stat-card .stat-trend.down { color: #991b1b; background: #fee2e2; }

@media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(4, 1fr); } }
@media (max-width: 768px) { .stats-grid { grid-template-columns: repeat(3, 1fr); gap: 6px; } }
@media (max-width: 480px) { .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 4px; } }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
