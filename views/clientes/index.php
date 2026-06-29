<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-users"></i></span>
            Clientes
        </h1>
        <p class="page-subtitle">Gestión de clientes</p>
    </div>
    <div class="header-right">
        <a href="<?= BASE_URL ?>?url=clientes/crear" class="btn-primary-modern">
            <i class="fas fa-plus"></i> Nuevo Cliente
        </a>
    </div>
</div>

<div class="table-container">
    <div class="table-toolbar">
        <div class="toolbar-left">
            <span class="total-registros">
                <i class="fas fa-database"></i> <?= count($clientes) ?> registros
            </span>
        </div>
        <div class="toolbar-right">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="buscarCliente" class="search-input" placeholder="Buscar cliente...">
            </div>
        </div>
    </div>

    <div class="table-responsive-modern">
        <table class="table-modern" id="tablaClientes">
            <thead>
                <tr>
                    <th class="col-nombre">Nombre</th>
                    <th class="col-ruc">RUC/DNI</th>
                    <th class="col-direccion">Dirección</th>
                    <th class="col-zona">Zona</th>
                    <th class="col-telefono">Teléfono</th>
                    <th class="col-estado">Estado</th>
                    <th class="col-acciones">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $c): ?>
                    <tr>
                        <td class="col-nombre"><?= $c['nombre'] ?></td>
                        <td class="col-ruc"><?= $c['ruc_dni'] ?></td>
                        <td class="col-direccion"><?= $c['direccion_fiscal'] ?></td>
                        <td class="col-zona"><?= $c['zona'] ?></td>
                        <td class="col-telefono"><?= $c['telefono'] ?></td>
                        <td class="col-estado">
                            <span class="badge <?= $c['activo'] ? 'bg-success' : 'bg-danger' ?>">
                                <?= $c['activo'] ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </td>
                        <td class="col-acciones">
                            <div class="acciones-group">
                                <?php if ($c['activo']): ?>
                                    <a href="<?= BASE_URL ?>?url=clientes/editar/<?= $c['id'] ?>" class="btn-action btn-edit" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>?url=clientes/eliminar/<?= $c['id'] ?>" class="btn-action btn-delete" onclick="return confirm('¿Desactivar este cliente?')" title="Desactivar">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= BASE_URL ?>?url=clientes/reactivar/<?= $c['id'] ?>" class="btn-action btn-success" onclick="return confirm('¿Reactivar este cliente?')" title="Reactivar">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                
                <?php if (empty($clientes)): ?>
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-content">
                                <i class="fas fa-users"></i>
                                <p>No hay clientes registrados</p>
                                <a href="<?= BASE_URL ?>?url=clientes/crear" class="btn-empty-action">
                                    <i class="fas fa-plus"></i> Crear primer cliente
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('buscarCliente');
    const rows = document.querySelectorAll('#tablaClientes tbody tr');
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const term = this.value.toLowerCase().trim();
            rows.forEach(function(row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }
});
</script>

<style>
.acciones-group {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
