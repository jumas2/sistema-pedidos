<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon success"><i class="fas fa-check-circle"></i></span>
            Confirmar Entrega
        </h1>
        <p class="page-subtitle">Confirma la entrega del pedido #<?= $guia['numero_guia'] ?? '' ?></p>
    </div>
    <div class="header-right">
        <a href="<?= BASE_URL ?>logistica/misDespachos" class="btn-secondary-modern">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <i class="fas fa-truck"></i> Datos del Despacho
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Guía</th>
                        <td><strong><?= $guia['numero_guia'] ?? 'N/A' ?></strong></td>
                    </tr>
                    <tr>
                        <th>Pedido</th>
                        <td><?= $guia['numero_pedido'] ?? 'N/A' ?></td>
                    </tr>
                    <tr>
                        <th>Cliente</th>
                        <td><?= $guia['cliente_nombre'] ?? 'N/A' ?></td>
                    </tr>
                    <tr>
                        <th>Dirección de Entrega</th>
                        <td><?= $guia['direccion_entrega'] ?? 'No especificada' ?></td>
                    </tr>
                    <tr>
                        <th>Observaciones</th>
                        <td><?= $guia['observacion'] ?? 'Sin observaciones' ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-info text-white">
                <i class="fas fa-camera"></i> Confirmación de Entrega
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>logistica/confirmarEntrega/<?= $guia['id'] ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Estado de Entrega *</label>
                        <select name="estado" class="form-control" required>
                            <option value="Entregado">✅ Entregado</option>
                            <option value="Pendiente">⏳ Pendiente</option>
                            <option value="Cancelado">❌ Cancelado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>¿Recibió el pedido?</label>
                        <select name="recibido" class="form-control">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Persona que recibió</label>
                        <input type="text" name="recibe_nombre" class="form-control" placeholder="Nombre de la persona">
                    </div>
                    <div class="form-group">
                        <label>DNI de quien recibe</label>
                        <input type="text" name="recibe_dni" class="form-control" placeholder="DNI">
                    </div>
                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea name="observacion" class="form-control" rows="2" placeholder="Detalles de la entrega"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-check"></i> Confirmar Entrega
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.form-group { margin-bottom: 15px; }
.form-group label { font-weight: 600; font-size: 13px; color: #0f172a; display: block; margin-bottom: 4px; }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
