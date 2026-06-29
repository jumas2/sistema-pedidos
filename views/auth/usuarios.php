<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- ========================================== -->
<!-- HEADER -->
<!-- ========================================== -->
<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-user-cog"></i></span>
            Usuarios
        </h1>
        <p class="page-subtitle">Gestión de usuarios, áreas y roles</p>
    </div>
    <div class="header-right">
    </div>
</div>

<!-- ========================================== -->
<!-- TABLA DE USUARIOS -->
<!-- ========================================== -->
<div class="table-container">
    <div class="table-toolbar">
        <div class="toolbar-left">
            <span class="total-registros">
                <i class="fas fa-database"></i> <?= count($usuarios) ?> registros
                <?php if (isset($_GET['estado']) && $_GET['estado'] === 'inactivos'): ?>
                    <span class="badge bg-danger">Inactivos</span>
                <?php endif; ?>
            </span>
        </div>
        <div class="toolbar-right">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="buscarUsuario" class="search-input" placeholder="Buscar usuario...">
            </div>
        </div>
    </div>

    <div class="table-responsive-modern">
        <table class="table-modern" id="tablaUsuarios">
            <thead>
                <tr>
                    <th class="col-id">ID</th>
                    <th class="col-nombre">Nombre</th>
                    <th class="col-ruc">Email</th>
                    <th class="col-direccion">Rol</th>
                    <th class="col-direccion">Área</th>
                    <th class="col-telefono">Estado</th>
                    <th class="col-acciones">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td class="col-id"><?= $u['id'] ?></td>
                        <td class="col-nombre">
                            <div class="cliente-nombre-with-avatar">
                                <span class="cliente-avatar-sm">
                                    <?php 
                                        $iniciales = '';
                                        $nombre = $u['nombre'] ?? '';
                                        $palabras = explode(' ', $nombre);
                                        foreach ($palabras as $palabra) {
                                            if (!empty($palabra)) {
                                                $iniciales .= strtoupper($palabra[0]);
                                            }
                                        }
                                        $iniciales = substr($iniciales, 0, 2);
                                    ?>
                                    <span class="avatar-text"><?= $iniciales ?: '?' ?></span>
                                </span>
                                <?= $u['nombre'] ?>
                            </div>
                        </td>
                        <td class="col-ruc"><?= $u['email'] ?></td>
                        <td class="col-direccion">
                            <?php 
                                $rolNombre = $u['rol_nombre'] ?? 'sin_rol';
                                $rolColors = [
                                    'super_admin' => 'dark',
                                    'admin' => 'danger',
                                    'admin_ventas' => 'info',
                                    'admin_logistica' => 'warning',
                                    'vendedor' => 'secondary',
                                    'asistente_logistica' => 'primary',
                                    'sin_rol' => 'secondary'
                                ];
                                $rolColor = $rolColors[$rolNombre] ?? 'secondary';
                                $rolLabels = [
                                    'super_admin' => 'Super Admin',
                                    'admin' => 'Admin',
                                    'admin_ventas' => 'Admin Ventas',
                                    'admin_logistica' => 'Admin Logística',
                                    'vendedor' => 'Vendedor',
                                    'asistente_logistica' => 'Asist. Logística',
                                    'sin_rol' => 'Sin rol'
                                ];
                                $rolLabel = $rolLabels[$rolNombre] ?? $rolNombre;
                            ?>
                            <span class="badge bg-<?= $rolColor ?>">
                                <?= $rolLabel ?>
                            </span>
                        </td>
                        <td class="col-direccion"><?= $u['area_nombre'] ?? 'Sin área' ?></td>
                        <td class="col-telefono">
                            <span class="badge <?= $u['activo'] ? 'bg-success' : 'bg-danger' ?>">
                                <?= $u['activo'] ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </td>
                        <td class="col-acciones">
                            <div class="acciones-group">
                                <?php if ($u['activo'] == 1): ?>
                                    <a href="<?= BASE_URL ?>?url=usuarios/editar/<?= $u['id'] ?>" class="btn-action btn-edit" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if (in_array($u['rol_nombre'] ?? '', ['super_admin', 'admin_ventas', 'vendedor'])): ?>
                                        <a href="<?= BASE_URL ?>?url=usuarios/clientes/<?= $u['id'] ?>" class="btn-action btn-info" title="Clientes">
                                            <i class="fas fa-users"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($u['id'] != $_SESSION['usuario']['id'] && esSuperAdmin()): ?>
                                        <a href="<?= BASE_URL ?>?url=usuarios/eliminar/<?= $u['id'] ?>" class="btn-action btn-delete" onclick="return confirm('¿Desactivar este usuario?')" title="Desactivar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="<?= BASE_URL ?>?url=usuarios/reactivar/<?= $u['id'] ?>" class="btn-action btn-success" onclick="return confirm('¿Reactivar este usuario?')" title="Reactivar">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-content">
                                <i class="fas fa-users"></i>
                                <p><?= isset($_GET['estado']) && $_GET['estado'] === 'inactivos' ? 'No hay usuarios inactivos' : 'No hay usuarios registrados' ?></p>
                                <a href="<?= BASE_URL ?>?url=usuarios/crear" class="btn-empty-action">
                                    <i class="fas fa-plus"></i> Crear primer usuario
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
    const searchInput = document.getElementById('buscarUsuario');
    const rows = document.querySelectorAll('#tablaUsuarios tbody tr');
    
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
.btn-group-modern {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.btn-secondary-modern.active {
    background: #4f46e5;
    color: white;
    border-color: #4f46e5;
}
.btn-secondary-modern.active:hover {
    background: #4338ca;
    color: white;
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
