<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-history"></i></span>
            Historial de Vehículos
        </h1>
        <p class="page-subtitle">
            Historial de asignaciones de vehículos para: 
            <strong><?= $transportistaData['nombre'] ?? '' ?> <?= $transportistaData['apellido'] ?? '' ?></strong>
        </p>
    </div>
    <div class="header-right">
        <a href="<?= BASE_URL ?>logistica/transportistas" class="btn-secondary-modern">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-info text-white">
        <i class="fas fa-list"></i> Historial de Asignaciones
    </div>
    <div class="card-body">
        <?php if (!empty($historial) && count($historial) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha Asignación</th>
                            <th>Vehículo</th>
                            <th>Tipo</th>
                            <th>Fecha Devolución</th>
                            <th>Registrado por</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historial as $h): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($h['fecha_asignacion'])) ?></td>
                                <td>
                                    <strong><?= $h['placa'] ?? 'N/A' ?></strong><br>
                                    <small><?= ($h['marca'] ?? '') . ' ' . ($h['modelo'] ?? '') ?></small>
                                </td>
                                <td>
                                    <span class="badge <?= $h['tipo_asignacion'] === 'permanente' ? 'bg-success' : 'bg-warning' ?>">
                                        <?= ucfirst($h['tipo_asignacion'] ?? 'temporal') ?>
                                    </span>
                                </td>
                                <td>
                                    <?= $h['fecha_devolucion'] ? date('d/m/Y', strtotime($h['fecha_devolucion'])) : '<span class="text-warning">En uso</span>' ?>
                                </td>
                                <td><?= $h['usuario_nombre'] ?? 'Sistema' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted"></i>
                <p class="text-muted mt-3">No hay historial de asignaciones para este transportista.</p>
                <p class="text-muted small">Asigna un vehículo para empezar a registrar el historial.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
