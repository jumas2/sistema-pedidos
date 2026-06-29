<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon warning"><i class="fas fa-edit"></i></span>
            Editar Producto
        </h1>
        <p class="page-subtitle">Actualizar información del producto: <strong><?= $producto['nombre'] ?? '' ?></strong></p>
    </div>
    <div class="header-right">
        <a href="<?= BASE_URL ?>?url=productos" class="btn-secondary-modern">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>?url=productos/editar/<?= $producto['id'] ?>" enctype="multipart/form-data" id="formProducto">
            <div class="row">
                <!-- Código -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label><i class="fas fa-barcode text-primary"></i> Código *</label>
                        <input type="text" name="codigo" class="form-control" value="<?= $producto['codigo'] ?? '' ?>" required>
                    </div>
                </div>
                
                <!-- Nombre -->
                <div class="col-md-8">
                    <div class="form-group">
                        <label><i class="fas fa-tag text-primary"></i> Nombre *</label>
                        <input type="text" name="nombre" class="form-control" value="<?= $producto['nombre'] ?? '' ?>" required>
                    </div>
                </div>
                
                <!-- Descripción -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label><i class="fas fa-align-left text-primary"></i> Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="2"><?= $producto['descripcion'] ?? '' ?></textarea>
                    </div>
                </div>
                
                <!-- Categoría -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label><i class="fas fa-layer-group text-primary"></i> Categoría</label>
                        <select name="categoria_id" class="form-select">
                            <option value="">Sin categoría</option>
                            <?php foreach ($categorias as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= ($producto['categoria_id'] == $c['id']) ? 'selected' : '' ?>>
                                    <?= $c['nombre'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Línea -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label><i class="fas fa-stream text-primary"></i> Línea</label>
                        <select name="linea_id" class="form-select">
                            <option value="">Sin línea</option>
                            <?php foreach ($lineas as $l): ?>
                                <option value="<?= $l['id'] ?>" <?= ($producto['linea_id'] == $l['id']) ? 'selected' : '' ?>>
                                    <?= $l['nombre'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Unidad de Medida -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label><i class="fas fa-ruler text-primary"></i> Unidad de Medida</label>
                        <select name="unidad_medida" class="form-select">
                            <option value="UNIDAD" <?= ($producto['unidad_medida'] ?? 'UNIDAD') === 'UNIDAD' ? 'selected' : '' ?>>Unidad</option>
                            <option value="LITRO" <?= ($producto['unidad_medida'] ?? '') === 'LITRO' ? 'selected' : '' ?>>Litro</option>
                            <option value="KILO" <?= ($producto['unidad_medida'] ?? '') === 'KILO' ? 'selected' : '' ?>>Kilo</option>
                            <option value="GRAMO" <?= ($producto['unidad_medida'] ?? '') === 'GRAMO' ? 'selected' : '' ?>>Gramo</option>
                            <option value="MILILITRO" <?= ($producto['unidad_medida'] ?? '') === 'MILILITRO' ? 'selected' : '' ?>>Mililitro</option>
                            <option value="CAJA" <?= ($producto['unidad_medida'] ?? '') === 'CAJA' ? 'selected' : '' ?>>Caja</option>
                            <option value="PAQUETE" <?= ($producto['unidad_medida'] ?? '') === 'PAQUETE' ? 'selected' : '' ?>>Paquete</option>
                        </select>
                    </div>
                </div>
                
                <!-- Precio -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label><i class="fas fa-money-bill-wave text-success"></i> Precio *</label>
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input type="number" name="precio" class="form-control" step="0.01" value="<?= $producto['precio'] ?? 0 ?>" required>
                        </div>
                    </div>
                </div>
                
                <!-- Moneda -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label><i class="fas fa-dollar-sign text-success"></i> Moneda</label>
                        <select name="moneda" class="form-select">
                            <option value="PEN" <?= ($producto['moneda'] ?? 'PEN') === 'PEN' ? 'selected' : '' ?>>Soles (S/)</option>
                            <option value="USD" <?= ($producto['moneda'] ?? '') === 'USD' ? 'selected' : '' ?>>Dólares ($)</option>
                        </select>
                    </div>
                </div>
                
                <!-- Stock -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label><i class="fas fa-cubes text-warning"></i> Stock</label>
                        <input type="number" name="stock" class="form-control" value="<?= $producto['stock'] ?? 0 ?>">
                    </div>
                </div>
                
                <!-- Stock Mínimo -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label><i class="fas fa-exclamation-triangle text-warning"></i> Stock Mínimo</label>
                        <input type="number" name="stock_minimo" class="form-control" value="<?= $producto['stock_minimo'] ?? 0 ?>">
                    </div>
                </div>
                
                <!-- Ubicación -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-map-pin text-primary"></i> Ubicación</label>
                        <input type="text" name="ubicacion" class="form-control" value="<?= $producto['ubicacion'] ?? '' ?>" placeholder="Ej: Estante A1">
                    </div>
                </div>
                
                <!-- Estado -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-power-off text-primary"></i> Estado</label>
                        <div class="form-check mt-2">
                            <input type="checkbox" name="activo" class="form-check-input" value="1" <?= ($producto['activo'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label">Producto activo</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <!-- Botones -->
            <div class="text-center">
                <button type="submit" class="btn-primary-modern" style="padding: 12px 40px; font-size: 16px; border-radius: 12px;">
                    <i class="fas fa-save"></i> Actualizar Producto
                </button>
                <a href="<?= BASE_URL ?>?url=productos" class="btn-secondary-modern" style="padding: 12px 30px; font-size: 16px; border-radius: 12px;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.form-group {
    margin-bottom: 18px;
}
.form-group label {
    font-weight: 600;
    font-size: 14px;
    color: #0f172a;
    margin-bottom: 6px;
    display: block;
}
.form-group label i {
    margin-right: 6px;
}
.form-control, .form-select {
    border-radius: 10px;
    border: 1.5px solid #e2e8f0;
    padding: 10px 14px;
    font-size: 14px;
    width: 100%;
    background: #fafbfc;
    transition: all 0.2s ease;
}
.form-control:focus, .form-select:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.08);
    outline: none;
    background: #ffffff;
}
.form-check {
    padding-top: 8px;
}
.form-check-input {
    width: 18px;
    height: 18px;
    margin-top: 2px;
}
.form-check-input:checked {
    background-color: #4f46e5;
    border-color: #4f46e5;
}
.form-check-label {
    font-size: 14px;
    color: #0f172a;
    margin-left: 6px;
}
.input-group-text {
    background: #f1f5f9;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px 0 0 10px;
    font-weight: 600;
    color: #0f172a;
}
hr {
    margin: 24px 0;
    border: none;
    border-top: 2px dashed #e2e8f0;
}
.btn-primary-modern {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 40px;
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 16px;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
}
.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(79, 70, 229, 0.4);
    color: #fff;
    text-decoration: none;
}
.btn-secondary-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 30px;
    background: #fff;
    color: #0f172a;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
    text-decoration: none;
    transition: all 0.3s ease;
}
.btn-secondary-modern:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
    text-decoration: none;
}
@media (max-width: 768px) {
    .btn-primary-modern, .btn-secondary-modern {
        width: 100%;
        justify-content: center;
    }
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
