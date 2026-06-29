<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- HEADER -->
<div class="clientes-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-user-plus"></i></span>
            Nuevo Cliente
        </h1>
        <p class="page-subtitle">Registrar un nuevo cliente en el sistema</p>
    </div>
    <div class="header-right">
        <a href="<?= BASE_URL ?>clientes" class="btn-secondary-modern">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<!-- FORMULARIO -->
<div class="form-card">
    <form method="POST" action="<?= BASE_URL ?>clientes/crear">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label>Nombre *</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label>RUC/DNI *</label>
                    <input type="text" class="form-control" name="ruc_dni" required>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label>Dirección Fiscal</label>
                    <input type="text" class="form-control" name="direccion_fiscal">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label>Zona</label>
                    <input type="text" class="form-control" name="zona" placeholder="Ej: Miraflores">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label>Teléfono</label>
                    <input type="text" class="form-control" name="telefono">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email">
                </div>
            </div>
        </div>
        <div class="text-center mt-3">
            <button type="submit" class="btn-primary-modern">
                <i class="fas fa-save"></i> Guardar Cliente
            </button>
            <a href="<?= BASE_URL ?>clientes" class="btn-secondary-modern ms-2">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
