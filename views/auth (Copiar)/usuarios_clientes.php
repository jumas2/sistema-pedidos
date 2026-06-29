<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- HEADER -->
<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-users"></i></span>
            Asignar Clientes
        </h1>
        <p class="page-subtitle">Clientes asignados a: <strong><?= $user['nombre'] ?></strong></p>
    </div>
    <div class="header-right">
        <a href="<?= BASE_URL ?>usuarios" class="btn-secondary-modern">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<!-- ========================================== -->
<!-- CLIENTES ASIGNADOS -->
<!-- ========================================== -->
<div class="card">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-check-circle"></i> Clientes Asignados</h5>
        <span class="badge bg-light text-dark"><?= count($clientesAsignados) ?> clientes</span>
    </div>
    <div class="card-body">
        <?php if (count($clientesAsignados) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>RUC/DNI</th>
                            <th>Teléfono</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientesAsignados as $c): ?>
                            <tr>
                                <td><?= $c['id'] ?></td>
                                <td><?= $c['nombre'] ?></td>
                                <td><?= $c['ruc_dni'] ?></td>
                                <td><?= $c['telefono'] ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>usuarios/eliminarCliente/<?= $user['id'] ?>/<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Remover este cliente?')">
                                        <i class="fas fa-times"></i> Remover
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No hay clientes asignados a este usuario
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ========================================== -->
<!-- CLIENTES DISPONIBLES -->
<!-- ========================================== -->
<div class="card mt-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-plus-circle"></i> Asignar Clientes</h5>
    </div>
    <div class="card-body">
        <?php if (count($clientesDisponibles) > 0): ?>
            <form method="POST" action="<?= BASE_URL ?>usuarios/asignarCliente/<?= $user['id'] ?>">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Seleccionar cliente</label>
                            <select class="form-control" name="cliente_id" required>
                                <option value="">Seleccionar cliente...</option>
                                <?php foreach ($clientesDisponibles as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?> (<?= $c['ruc_dni'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-user-plus"></i> Asignar Cliente
                        </button>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> Todos los clientes ya están asignados
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
