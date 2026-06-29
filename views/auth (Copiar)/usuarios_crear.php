<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- HEADER -->
<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-user-plus"></i></span>
            Nuevo Usuario
        </h1>
        <p class="page-subtitle">Registrar un nuevo usuario en el sistema</p>
    </div>
    <div class="header-right">
        <a href="<?= BASE_URL ?>usuarios" class="btn-secondary-modern">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="form-card">
    <form method="POST" action="<?= BASE_URL ?>usuarios/crear">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label>Nombre *</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label>Email *</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label>Contraseña *</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label>Teléfono</label>
                    <input type="text" class="form-control" name="telefono">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label>Rol *</label>
                    <select class="form-control" name="rol_id" required>
                        <option value="">Seleccionar rol</option>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r['id'] ?>">
                                <?php 
                                    $labels = [
                                        'super_admin' => 'Super Admin (TI)',
                                        'admin' => 'Administrador',
                                        'admin_ventas' => 'Admin Ventas',
                                        'admin_logistica' => 'Admin Logística',
                                        'vendedor' => 'Vendedor',
                                        'asistente_logistica' => 'Asistente Logística'
                                    ];
                                    echo $labels[$r['nombre']] ?? ucfirst(str_replace('_', ' ', $r['nombre']));
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label>Área *</label>
                    <select class="form-control" name="area_id" required>
                        <option value="">Seleccionar área</option>
                        <?php foreach ($areas as $a): ?>
                            <option value="<?= $a['id'] ?>"><?= $a['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="activo" value="1" checked>
                        <label class="form-check-label">Usuario activo</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-3">
            <button type="submit" class="btn-primary-modern">
                <i class="fas fa-save"></i> Guardar Usuario
            </button>
            <a href="<?= BASE_URL ?>usuarios" class="btn-secondary-modern ms-2">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
