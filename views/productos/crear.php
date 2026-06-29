<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- ========================================== -->
<!-- HEADER -->
<!-- ========================================== -->
<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-box"></i></span>
            Nuevo Producto
        </h1>
        <p class="page-subtitle" style="font-size:14px; color:#94a3b8; margin-top:2px;">
            Registra un nuevo producto o servicio
        </p>
    </div>
    <div class="header-right">
        <a href="<?= BASE_URL ?>productos" class="btn-secondary-modern">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<!-- ========================================== -->
<!-- FORMULARIO COMPACTO -->
<!-- ========================================== -->
<div class="form-card" style="padding:20px 24px;">

    <form method="POST" action="<?= BASE_URL ?>productos/crear" id="formProducto">
        
        <!-- ========================================== -->
        <!-- FILA 1: Código + Generar -->
        <!-- ========================================== -->
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>Código</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="codigo" id="codigo" placeholder="Ingresa un código" required>
                        <button type="button" class="btn btn-outline-primary" onclick="generarCodigoAuto()" style="border-radius:0 10px 10px 0; padding:8px 16px; font-size:13px;">
                            <i class="fas fa-magic"></i> Auto
                        </button>
                    </div>
                    <small class="text-muted">El código debe ser único. Puedes generarlo automáticamente.</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Moneda</label>
                    <select class="form-control" name="moneda" required>
                        <option value="PEN">Soles (PEN)</option>
                        <option value="USD">Dólares (USD)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- FILA 2: Nombre -->
        <!-- ========================================== -->
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" class="form-control" name="nombre" placeholder="Ej: DESINFECTANTE BIOCIDA 5L" required>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- FILA 3: Descripción -->
        <!-- ========================================== -->
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea class="form-control" name="descripcion" rows="2" placeholder="Descripción detallada del producto"></textarea>
                    <small class="text-muted">La descripción debe ser única en el sistema.</small>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- FILA 4: Categoría + Línea -->
        <!-- ========================================== -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Categoría</label>
                    <select class="form-control" name="categoria_id" id="categoriaSelect">
                        <option value="">Seleccionar categoría</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Línea</label>
                    <select class="form-control" name="linea_id">
                        <option value="">Seleccionar línea</option>
                        <?php foreach ($lineas as $l): ?>
                            <option value="<?= $l['id'] ?>"><?= $l['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- FILA 5: Unidad + Ubicación -->
        <!-- ========================================== -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Unidad de Medida</label>
                    <select class="form-control" name="unidad_medida">
                        <option value="UNIDAD">Unidad</option>
                        <option value="LITRO">Litro</option>
                        <option value="KILO">Kilo</option>
                        <option value="PAQUETE">Paquete</option>
                        <option value="METRO">Metro</option>
                        <option value="MILILITRO">Mililitro</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Ubicación</label>
                    <input type="text" class="form-control" name="ubicacion" placeholder="Estante, pasillo, etc.">
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- FILA 6: Precio + Stock + Stock Mínimo -->
        <!-- ========================================== -->
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Precio *</label>
                    <input type="number" class="form-control" name="precio" step="0.01" placeholder="0.00" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" class="form-control" name="stock" value="0">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Stock Mínimo</label>
                    <input type="number" class="form-control" name="stock_minimo" value="0">
                    <small class="text-muted">Alerta cuando el stock baje de aquí</small>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- BOTONES -->
        <!-- ========================================== -->
        <div class="text-center mt-3" style="padding-top:12px; border-top:1px solid #f1f5f9;">
            <button type="submit" class="btn-primary-modern" style="min-width:160px;">
                <i class="fas fa-save"></i> Guardar Producto
            </button>
            <a href="<?= BASE_URL ?>productos" class="btn-secondary-modern ms-2">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<!-- ========================================== -->
<!-- SCRIPTS -->
<!-- ========================================== -->
<script>
function generarCodigoAuto() {
    fetch('<?= BASE_URL ?>productos/generarCodigo')
        .then(response => response.json())
        .then(data => {
            document.getElementById('codigo').value = data.codigo;
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'No se pudo generar el código automático', 'error');
        });
}

document.addEventListener('DOMContentLoaded', function() {
    generarCodigoAuto();
});
</script>

<!-- ========================================== -->
<!-- ESTILOS ADICIONALES -->
<!-- ========================================== -->
<style>
.form-card {
    background: white;
    border-radius: 16px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}

.form-group {
    margin-bottom: 14px;
}

.form-group label {
    font-weight: 600;
    font-size: 13px;
    color: #0f172a;
    margin-bottom: 4px;
    display: block;
}

.form-group .form-control {
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 8px 14px;
    font-size: 14px;
    transition: all 0.2s ease;
    height: 40px;
}

.form-group .form-control:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.10);
}

.form-group textarea.form-control {
    height: auto;
    resize: vertical;
}

.form-group .input-group .form-control {
    border-radius: 10px 0 0 10px;
}

.form-group .input-group .btn {
    border: 1.5px solid #e2e8f0;
    border-left: none;
    background: #f8fafc;
    color: #4f46e5;
    font-weight: 600;
}

.form-group .input-group .btn:hover {
    background: #eef2ff;
}

.text-muted {
    font-size: 11px;
    color: #94a3b8 !important;
    margin-top: 2px;
    display: block;
}

@media (max-width: 768px) {
    .form-card {
        padding: 16px !important;
    }
    .form-group {
        margin-bottom: 10px;
    }
    .form-group .form-control {
        height: 38px;
        font-size: 13px;
    }
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
