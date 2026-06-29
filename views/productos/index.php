<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-boxes"></i></span>
            Productos
        </h1>
        <p class="page-subtitle">Gestión de productos</p>
    </div>
    <div class="header-right">
        <a href="<?= BASE_URL ?>?url=productos/crear" class="btn-primary-modern">
            <i class="fas fa-plus"></i> Nuevo Producto
        </a>
    </div>
</div>

<div class="table-container">
    <div class="table-toolbar">
        <div class="toolbar-left">
            <span class="total-registros">
                <i class="fas fa-database"></i> <?= count($productos) ?> registros
            </span>
        </div>
        <div class="toolbar-right">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="buscarProducto" class="search-input" placeholder="Buscar producto...">
            </div>
        </div>
    </div>

    <div class="table-responsive-modern">
        <table class="table-modern" id="tablaProductos">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p): ?>
                    <tr>
                        <td><strong><?= $p['codigo'] ?></strong></td>
                        <td><?= $p['nombre'] ?></td>
                        <td><?= $p['categoria_nombre'] ?? 'Sin categoría' ?></td>
                        <td><strong><?= $p['moneda'] === 'USD' ? '$' : 'S/ ' ?><?= number_format($p['precio'], 2) ?></strong></td>
                        <td>
                            <?php if ($p['stock'] <= 5): ?>
                                <span class="badge bg-danger"><?= $p['stock'] ?></span>
                            <?php else: ?>
                                <?= $p['stock'] ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?= $p['activo'] ? 'bg-success' : 'bg-danger' ?>">
                                <?= $p['activo'] ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </td>
                        <td>
                            <div class="acciones-group">
                                <?php if ($p['activo']): ?>
                                    <a href="<?= BASE_URL ?>?url=productos/editar/<?= $p['id'] ?>" class="btn-action btn-edit" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>?url=productos/eliminar/<?= $p['id'] ?>" class="btn-action btn-delete" onclick="return confirm('¿Desactivar este producto?')" title="Desactivar">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= BASE_URL ?>?url=productos/reactivar/<?= $p['id'] ?>" class="btn-action btn-success" onclick="return confirm('¿Reactivar este producto?')" title="Reactivar">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                
                <?php if (empty($productos)): ?>
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-content">
                                <i class="fas fa-boxes"></i>
                                <p>No hay productos registrados</p>
                                <a href="<?= BASE_URL ?>?url=productos/crear" class="btn-empty-action">
                                    <i class="fas fa-plus"></i> Crear primer producto
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
    const searchInput = document.getElementById('buscarProducto');
    const rows = document.querySelectorAll('#tablaProductos tbody tr');
    
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
