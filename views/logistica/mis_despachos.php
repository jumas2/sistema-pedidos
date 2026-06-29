<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-truck"></i></span>
            Mis Despachos
        </h1>
        <p class="page-subtitle">Lista de despachos asignados a mi persona</p>
    </div>
    <div class="header-right">
        <a href="<?= BASE_URL ?>dashboard" class="btn-secondary-modern">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-info text-white">
        <i class="fas fa-list"></i> Despachos Asignados
    </div>
    <div class="card-body">
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-<?= $_SESSION['flash']['type'] ?>"><?= $_SESSION['flash']['message'] ?></div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>N° Guía</th>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Dirección</th>
                        <th>Fecha Emisión</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($misGuias)): ?>
                        <?php foreach ($misGuias as $g): ?>
                            <tr>
                                <td><strong><?= $g['numero_guia'] ?></strong></td>
                                <td><?= $g['numero_pedido'] ?? '-' ?></td>
                                <td><?= $g['cliente_nombre'] ?? '-' ?></td>
                                <td><?= $g['direccion_entrega'] ?? 'No especificada' ?></td>
                                <td><?= date('d/m/Y', strtotime($g['fecha_emision'])) ?></td>
                                <td>
                                    <span class="badge <?= $g['estado'] === 'Pendiente' ? 'bg-warning' : ($g['estado'] === 'En Proceso' ? 'bg-info' : ($g['estado'] === 'Entregado' ? 'bg-success' : ($g['estado'] === 'Cancelado' ? 'bg-danger' : 'bg-secondary'))) ?>">
                                        <?= $g['estado'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($g['estado'] !== 'Entregado' && $g['estado'] !== 'Cancelado'): ?>
                                        <a href="<?= BASE_URL ?>?url=logistica/confirmarEntrega/<?= $g['id'] ?>" class="btn btn-sm btn-success" title="Confirmar Entrega">
                                            <i class="fas fa-check-circle"></i> Entregar
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= BASE_URL ?>?url=logistica/ver/<?= $g['id'] ?>" class="btn btn-sm btn-info" title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                No tienes despachos asignados
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
