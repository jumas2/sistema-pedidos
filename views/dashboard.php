<?php require_once __DIR__ . '/layouts/header.php'; ?>

<!-- ========================================== -->
<!-- HEADER DEL DASHBOARD -->
<!-- ========================================== -->
<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-th-large"></i></span>
            Dashboard
        </h1>
        <p class="page-subtitle">Resumen general del sistema</p>
    </div>
    <div class="header-right">
        <a href="<?= BASE_URL ?>pedidos/crear" class="btn-primary-modern">
            <i class="fas fa-plus"></i> Nuevo Pedido
        </a>
    </div>
</div>

<!-- ========================================== -->
<!-- TARJETAS DE ESTADÍSTICAS - CON FILTRO -->
<!-- ========================================== -->
<div class="stats-grid">
    <!-- Pendientes -->
    <div class="stat-card" onclick="window.location.href='<?= BASE_URL ?>pedidos?estado=Pendiente'">
        <div class="stat-icon yellow">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <div class="stat-number"><?= $estadisticas['pendientes'] ?? 0 ?></div>
            <div class="stat-label">Pendientes</div>
        </div>
        <div class="stat-trend up">
            <i class="fas fa-arrow-up"></i> 12%
        </div>
    </div>
    
    <!-- Atendidos -->
    <div class="stat-card" onclick="window.location.href='<?= BASE_URL ?>pedidos?estado=Atendido'">
        <div class="stat-icon green">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <div class="stat-number"><?= $estadisticas['atendidos'] ?? 0 ?></div>
            <div class="stat-label">Atendidos</div>
        </div>
        <div class="stat-trend up">
            <i class="fas fa-arrow-up"></i> 8%
        </div>
    </div>
    
    <!-- Total Pedidos -->
    <div class="stat-card" onclick="window.location.href='<?= BASE_URL ?>pedidos'">
        <div class="stat-icon blue">
            <i class="fas fa-file-invoice"></i>
        </div>
        <div>
            <div class="stat-number"><?= $estadisticas['total'] ?? 0 ?></div>
            <div class="stat-label">Total Pedidos</div>
        </div>
        <div class="stat-trend up">
            <i class="fas fa-arrow-up"></i> 5%
        </div>
    </div>
    
    <!-- Clientes -->
    <div class="stat-card" onclick="window.location.href='<?= BASE_URL ?>clientes'">
        <div class="stat-icon purple">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <div class="stat-number"><?= $totalClientes ?? 0 ?></div>
            <div class="stat-label">Clientes</div>
        </div>
        <div class="stat-trend up">
            <i class="fas fa-arrow-up"></i> 3%
        </div>
    </div>
    
    <!-- Productos -->
    <div class="stat-card" onclick="window.location.href='<?= BASE_URL ?>productos'">
        <div class="stat-icon red">
            <i class="fas fa-boxes"></i>
        </div>
        <div>
            <div class="stat-number"><?= $totalProductos ?? 0 ?></div>
            <div class="stat-label">Productos</div>
        </div>
        <div class="stat-trend down">
            <i class="fas fa-arrow-down"></i> 2%
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- ACTIVIDAD RECIENTE + RESUMEN -->
<!-- ========================================== -->
<div class="dashboard-row">
    <!-- Actividad Reciente -->
    <div class="col-recent">
        <div class="recent-card">
            <div class="card-header">
                <h5><i class="fas fa-clock me-2" style="color: #4f46e5;"></i>Actividad Reciente</h5>
                <a href="<?= BASE_URL ?>pedidos" class="btn-link">Ver todos <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recientes)): ?>
                            <?php foreach ($recientes as $pedido): ?>
                                <tr>
                                    <td><strong><?= $pedido['numero_pedido'] ?></strong></td>
                                    <td><?= $pedido['cliente_nombre'] ?></td>
                                    <td><?= date('d/m', strtotime($pedido['fecha_pedido'])) ?></td>
                                    <td><strong>S/ <?= number_format($pedido['total'], 2) ?></strong></td>
                                    <td>
                                        <span class="estado-badge <?= $pedido['estado'] === 'Pendiente' ? 'estado-pendiente' : ($pedido['estado'] === 'Atendido' ? 'estado-atendido' : 'estado-anulado') ?>">
                                            <?= $pedido['estado'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>pedidos/ver/<?= $pedido['id'] ?>" class="btn-action btn-view" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox d-block mb-2"></i>
                                    No hay pedidos recientes
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Panel de Resumen Rápido -->
    <div class="col-summary">
        <div class="quick-summary">
            <div class="quick-summary-header">
                <h5><i class="fas fa-chart-pie me-2" style="color: #4f46e5;"></i>Resumen Rápido</h5>
            </div>
            <div class="quick-summary-body">
                <div class="summary-item">
                    <div class="summary-label">
                        <span>Pendientes</span>
                        <span><?= $estadisticas['pendientes'] ?? 0 ?></span>
                    </div>
                    <div class="summary-bar">
                        <div class="summary-bar-fill yellow-bg" style="width: <?= ($estadisticas['total'] > 0) ? round(($estadisticas['pendientes'] ?? 0) / $estadisticas['total'] * 100) : 0 ?>%;"></div>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">
                        <span>Atendidos</span>
                        <span><?= $estadisticas['atendidos'] ?? 0 ?></span>
                    </div>
                    <div class="summary-bar">
                        <div class="summary-bar-fill green-bg" style="width: <?= ($estadisticas['total'] > 0) ? round(($estadisticas['atendidos'] ?? 0) / $estadisticas['total'] * 100) : 0 ?>%;"></div>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">
                        <span>Clientes</span>
                        <span><?= $totalClientes ?? 0 ?></span>
                    </div>
                    <div class="summary-bar">
                        <div class="summary-bar-fill purple-bg" style="width: <?= ($totalClientes ?? 0) > 0 ? min(100, round(($totalClientes ?? 0) / 20 * 100)) : 0 ?>%;"></div>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">
                        <span>Productos</span>
                        <span><?= $totalProductos ?? 0 ?></span>
                    </div>
                    <div class="summary-bar">
                        <div class="summary-bar-fill blue-bg" style="width: <?= ($totalProductos ?? 0) > 0 ? min(100, round(($totalProductos ?? 0) / 50 * 100)) : 0 ?>%;"></div>
                    </div>
                </div>
                <?php if (!empty($pendientes)): ?>
                <div class="summary-divider"></div>
                <div class="summary-item highlight">
                    <div class="summary-label">
                        <span><i class="fas fa-exclamation-circle" style="color: #f59e0b;"></i> Pendientes urgentes</span>
                        <span class="badge-urgent"><?= count($pendientes) ?></span>
                    </div>
                    <div style="font-size: 12px; color: #64748b; margin-top: 4px;">
                        <?php foreach (array_slice($pendientes, 0, 3) as $p): ?>
                            <div>• <?= $p['numero_pedido'] ?> - <?= $p['cliente_nombre'] ?></div>
                        <?php endforeach; ?>
                        <?php if (count($pendientes) > 3): ?>
                            <div class="text-muted" style="font-size: 11px;">+ <?= count($pendientes) - 3 ?> más...</div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- ESTILOS COMPACTOS -->
<!-- ========================================== -->
<style>
/* ========================================== */
/* TARJETAS COMPACTAS */
/* ========================================== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    background: white;
    border-radius: 14px;
    padding: 16px 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    gap: 14px;
    cursor: pointer;
    position: relative;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
}

.stat-card:active {
    transform: scale(0.97);
}

.stat-card .stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    flex-shrink: 0;
}

.stat-card .stat-icon.blue { background: #eef2ff; color: #4f46e5; }
.stat-card .stat-icon.green { background: #ecfdf5; color: #10b981; }
.stat-card .stat-icon.yellow { background: #fffbeb; color: #f59e0b; }
.stat-card .stat-icon.purple { background: #f5f3ff; color: #8b5cf6; }
.stat-card .stat-icon.red { background: #fef2f2; color: #ef4444; }

.stat-card .stat-number {
    font-size: 24px;
    font-weight: 800;
    line-height: 1.1;
    color: #0f172a;
}

.stat-card .stat-label {
    font-size: 12px;
    color: #64748b;
    font-weight: 500;
}

.stat-card .stat-trend {
    font-size: 10px;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 20px;
    margin-left: auto;
    white-space: nowrap;
}

.stat-card .stat-trend.up {
    color: #065f46;
    background: #d1fae5;
}

.stat-card .stat-trend.down {
    color: #991b1b;
    background: #fee2e2;
}

/* ========================================== */
/* DASHBOARD ROW - 2 COLUMNAS */
/* ========================================== */
.dashboard-row {
    display: flex;
    gap: 20px;
    margin-bottom: 0;
}

.col-recent {
    flex: 2;
    min-width: 0;
}

.col-summary {
    flex: 1;
    min-width: 0;
}

/* ========================================== */
/* RECENT CARD */
/* ========================================== */
.recent-card {
    background: white;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    height: 100%;
}

.recent-card .card-header {
    padding: 12px 18px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fafbfc;
}

.recent-card .card-header h5 {
    font-weight: 600;
    font-size: 14px;
    margin: 0;
}

.recent-card .card-header .btn-link {
    color: #4f46e5;
    font-weight: 600;
    font-size: 12px;
    text-decoration: none;
}

.recent-card .table {
    margin-bottom: 0;
    font-size: 13px;
}

.recent-card .table th {
    font-weight: 600;
    color: #64748b;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    background: #fafbfc;
    border-bottom: 1px solid #e2e8f0;
    padding: 8px 12px;
}

.recent-card .table td {
    vertical-align: middle;
    padding: 8px 12px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 12px;
}

.recent-card .table tbody tr:hover {
    background: #f8fafc;
}

/* ========================================== */
/* QUICK SUMMARY */
/* ========================================== */
.quick-summary {
    background: white;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    height: 100%;
}

.quick-summary-header {
    padding: 12px 18px;
    border-bottom: 1px solid #e2e8f0;
    background: #fafbfc;
}

.quick-summary-header h5 {
    font-weight: 600;
    font-size: 14px;
    margin: 0;
}

.quick-summary-body {
    padding: 14px 18px;
}

.summary-item {
    margin-bottom: 10px;
}

.summary-item:last-child {
    margin-bottom: 0;
}

.summary-item.highlight {
    background: #fffbeb;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #fde68a;
    margin-top: 8px;
}

.summary-item .summary-label {
    font-size: 12px;
    font-weight: 500;
    color: #0f172a;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.summary-item .summary-label .badge-urgent {
    background: #f59e0b;
    color: white;
    font-size: 10px;
    padding: 1px 10px;
    border-radius: 20px;
}

.summary-bar {
    height: 4px;
    background: #f1f5f9;
    border-radius: 4px;
    overflow: hidden;
    margin-top: 3px;
}

.summary-bar-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 1s ease;
}

.summary-bar-fill.yellow-bg { background: #f59e0b; }
.summary-bar-fill.green-bg { background: #10b981; }
.summary-bar-fill.purple-bg { background: #8b5cf6; }
.summary-bar-fill.blue-bg { background: #4f46e5; }

.summary-divider {
    border-top: 1px dashed #e2e8f0;
    margin: 10px 0;
}

/* ========================================== */
/* ESTADOS */
/* ========================================== */
.estado-badge {
    display: inline-flex;
    align-items: center;
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 10px;
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

/* ========================================== */
/* BOTONES DE ACCIÓN */
/* ========================================== */
.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    border: none;
    background: transparent;
    color: #64748b;
    transition: all 0.2s ease;
    text-decoration: none;
    font-size: 12px;
}

.btn-action:hover { text-decoration: none; }

.btn-view:hover {
    background: #e0f2fe;
    color: #0891b2;
}

/* ========================================== */
/* PAGE HEADER */
/* ========================================== */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 10px;
}

.page-title {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.title-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    font-size: 15px;
}

.title-icon.primary {
    background: #eef2ff;
    color: #4f46e5;
}

.page-subtitle {
    color: #64748b;
    margin: 2px 0 0 46px;
    font-size: 13px;
}

/* ========================================== */
/* BOTÓN NUEVO PEDIDO */
/* ========================================== */
.btn-primary-modern {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 13px;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
    color: #fff;
    text-decoration: none;
}

/* ========================================== */
/* RESPONSIVE */
/* ========================================== */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 992px) {
    .dashboard-row {
        flex-direction: column;
    }
    .col-recent, .col-summary {
        flex: 1;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .stat-card {
        padding: 12px 16px;
        gap: 10px;
    }
    .stat-card .stat-icon {
        width: 36px;
        height: 36px;
        font-size: 14px;
    }
    .stat-card .stat-number {
        font-size: 20px;
    }
    .stat-card .stat-label {
        font-size: 11px;
    }
    .stat-card .stat-trend {
        font-size: 9px;
        padding: 1px 6px;
    }
    .page-title {
        font-size: 18px;
    }
    .page-subtitle {
        margin-left: 0;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    .stat-card {
        padding: 10px 12px;
        flex-direction: column;
        text-align: center;
        gap: 4px;
    }
    .stat-card .stat-icon {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
    .stat-card .stat-number {
        font-size: 18px;
    }
    .stat-card .stat-trend {
        margin-left: 0;
    }
    .recent-card .table {
        font-size: 11px;
    }
    .recent-card .table th,
    .recent-card .table td {
        padding: 6px 8px;
    }
    .quick-summary-body {
        padding: 10px 12px;
    }
}
</style>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
