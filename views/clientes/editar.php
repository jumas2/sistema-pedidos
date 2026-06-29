<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- HEADER -->
<div class="clientes-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon warning"><i class="fas fa-user-edit"></i></span>
            Editar Cliente
        </h1>
        <p class="page-subtitle">Modificar los datos del cliente</p>
    </div>
    <div class="header-right">
        <a href="<?= BASE_URL ?>clientes" class="btn-secondary-modern">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<!-- FORMULARIO -->
<div class="form-card">
    <form method="POST" action="<?= BASE_URL ?>clientes/editar/<?= $cliente['id'] ?>">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label>Nombre *</label>
                    <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label>RUC/DNI *</label>
                    <input type="text" class="form-control" name="ruc_dni" value="<?= htmlspecialchars($cliente['ruc_dni']) ?>" required>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label>Dirección Fiscal</label>
                    <input type="text" class="form-control" name="direccion_fiscal" value="<?= htmlspecialchars($cliente['direccion_fiscal']) ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label>Zona</label>
                    <input type="text" class="form-control" name="zona" value="<?= htmlspecialchars($cliente['zona']) ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label>Teléfono</label>
                    <input type="text" class="form-control" name="telefono" value="<?= htmlspecialchars($cliente['telefono']) ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($cliente['email']) ?>">
                </div>
            </div>
        </div>
        <div class="text-center mt-3">
            <button type="submit" class="btn-warning-modern">
                <i class="fas fa-save"></i> Actualizar Cliente
            </button>
            <a href="<?= BASE_URL ?>clientes" class="btn-secondary-modern ms-2">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
