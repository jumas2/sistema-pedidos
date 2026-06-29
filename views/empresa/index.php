<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-building"></i></span>
            Configuración de Empresa
        </h1>
        <p class="page-subtitle">Datos de la empresa y configuración general</p>
    </div>
    <div class="header-right">
        <a href="<?= BASE_URL ?>dashboard" class="btn-secondary-modern">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>empresa/actualizar" enctype="multipart/form-data">
            <div class="row">
                <!-- Logo -->
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <?php if (!empty($data['logo'])): ?>
                            <img src="<?= BASE_URL . $data['logo'] ?>" alt="Logo" style="max-width:150px; max-height:150px; border-radius:12px; border:1px solid #e2e8f0; padding:8px;">
                        <?php else: ?>
                            <div style="width:150px; height:150px; background:#f8fafc; border:2px dashed #e2e8f0; border-radius:12px; display:flex; align-items:center; justify-content:center; margin:0 auto;">
                                <i class="fas fa-image fa-3x" style="color:#cbd5e1;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cambiar Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*" formaction="<?= BASE_URL ?>empresa/subirLogo">
                    </div>
                    <button type="submit" class="btn-secondary-modern" formaction="<?= BASE_URL ?>empresa/subirLogo">
                        <i class="fas fa-upload"></i> Subir Logo
                    </button>
                </div>
                
                <!-- Datos de la empresa -->
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Razón Social *</label>
                                <input type="text" name="nombre" class="form-control" value="<?= $data['nombre'] ?? '' ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>RUC *</label>
                                <input type="text" name="ruc" class="form-control" value="<?= $data['ruc'] ?? '' ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" name="telefono" class="form-control" value="<?= $data['telefono'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" name="direccion" class="form-control" value="<?= $data['direccion'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="<?= $data['email'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Teléfono Contacto</label>
                                <input type="text" name="telefono_contacto" class="form-control" value="<?= $data['telefono_contacto'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Moneda</label>
                                <select name="moneda" class="form-control">
                                    <option value="PEN" <?= ($data['moneda'] ?? 'PEN') === 'PEN' ? 'selected' : '' ?>>Soles (S/)</option>
                                    <option value="USD" <?= ($data['moneda'] ?? 'PEN') === 'USD' ? 'selected' : '' ?>>Dólares ($)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>IGV (%)</label>
                                <input type="number" name="igv" class="form-control" step="0.01" value="<?= $data['igv'] ?? 18.00 ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <!-- Configuración Visual -->
            <div class="row">
                <div class="col-md-12">
                    <h5 class="mb-3"><i class="fas fa-palette"></i> Personalización de Reportes</h5>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Color Primario</label>
                        <input type="color" name="color_primario" class="form-control" value="<?= $data['color_primario'] ?? '#4f46e5' ?>" style="height:50px; padding:4px;">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Color Secundario</label>
                        <input type="color" name="color_secundario" class="form-control" value="<?= $data['color_secundario'] ?? '#0f172a' ?>" style="height:50px; padding:4px;">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Color de Acento (Opcional)</label>
                        <input type="color" name="color_acento" class="form-control" value="<?= $data['color_acento'] ?? '#e2e8f0' ?>" style="height:50px; padding:4px;">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Pie de Página Personalizado</label>
                        <textarea name="pie_pagina" class="form-control" rows="2" placeholder="Ej: Teléfono: 2812949 - Email: info@bioservice.com"><?= $data['pie_pagina'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <div class="text-center">
                <button type="submit" class="btn-primary-modern">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="<?= BASE_URL ?>dashboard" class="btn-secondary-modern">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<style>
.form-group { margin-bottom: 16px; }
.form-group label {
    font-weight: 600;
    font-size: 13px;
    color: #0f172a;
    margin-bottom: 4px;
    display: block;
}
.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    padding: 8px 12px;
    font-size: 14px;
    width: 100%;
    background: #fafbfc;
    transition: 0.2s;
}
.form-control:focus, .form-select:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    outline: none;
    background: #fff;
}
input[type="color"].form-control {
    padding: 2px;
    height: 44px;
}
.btn-secondary-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: white;
    color: #0f172a;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
}
.btn-secondary-modern:hover {
    background: #f8fafc;
    color: #0f172a;
    text-decoration: none;
}
.btn-primary-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 22px;
    background: #4f46e5;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}
.btn-primary-modern:hover {
    background: #4338ca;
    color: #fff;
    text-decoration: none;
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
}
hr { margin: 24px 0; }
h5 { font-weight: 700; color: #0f172a; }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
