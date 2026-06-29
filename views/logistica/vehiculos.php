<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-car"></i></span>
            Gestión de Vehículos
        </h1>
        <p class="page-subtitle">Administración de la flota de vehículos</p>
    </div>
    <div class="header-right">
        <button type="button" class="btn-primary-modern" data-bs-toggle="modal" data-bs-target="#modalCrearVehiculo">
            <i class="fas fa-plus"></i> Nuevo Vehículo
        </button>
        <a href="<?= BASE_URL ?>logistica" class="btn-secondary-modern">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<!-- Estadísticas rápidas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center bg-success text-white">
            <div class="card-body">
                <h3><?= count(array_filter($vehiculos, fn($v) => $v['estado'] === 'Disponible')) ?></h3>
                <p>Disponibles</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-info text-white">
            <div class="card-body">
                <h3><?= count(array_filter($vehiculos, fn($v) => $v['estado'] === 'En Uso')) ?></h3>
                <p>En Uso</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-warning text-dark">
            <div class="card-body">
                <h3><?= count(array_filter($vehiculos, fn($v) => $v['estado'] === 'En Mantenimiento')) ?></h3>
                <p>En Mantenimiento</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-secondary text-white">
            <div class="card-body">
                <h3><?= count($vehiculos) ?></h3>
                <p>Total</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-info text-white">
        <i class="fas fa-list"></i> Lista de Vehículos
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Marca / Modelo</th>
                        <th>Año</th>
                        <th>Color</th>
                        <th>Tipo</th>
                        <th>Capacidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehiculos as $v): ?>
                        <tr>
                            <td><strong><?= $v['placa'] ?></strong></td>
                            <td><?= $v['marca'] . ' ' . $v['modelo'] ?></td>
                            <td><?= $v['anio'] ?></td>
                            <td><?= $v['color'] ?></td>
                            <td><?= $v['tipo'] ?></td>
                            <td><?= $v['capacidad_carga'] ?></td>
                            <td>
                                <span class="badge <?= $v['estado'] === 'Disponible' ? 'bg-success' : ($v['estado'] === 'En Uso' ? 'bg-info' : ($v['estado'] === 'En Mantenimiento' ? 'bg-warning' : 'bg-secondary')) ?>">
                                    <?= $v['estado'] ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditarVehiculo<?= $v['id'] ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="<?= BASE_URL ?>?url=logistica/eliminarVehiculo/<?= $v['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirmEliminar('¿Eliminar este vehículo?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        
                        <!-- Modal Editar -->
                        <div class="modal fade" id="modalEditarVehiculo<?= $v['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="<?= BASE_URL ?>logistica/actualizarVehiculo/<?= $v['id'] ?>">
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Vehículo</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Placa *</label>
                                                <input type="text" name="placa" class="form-control" value="<?= $v['placa'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Marca *</label>
                                                <input type="text" name="marca" class="form-control" value="<?= $v['marca'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Modelo *</label>
                                                <input type="text" name="modelo" class="form-control" value="<?= $v['modelo'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Año</label>
                                                <input type="number" name="anio" class="form-control" value="<?= $v['anio'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Color</label>
                                                <input type="text" name="color" class="form-control" value="<?= $v['color'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Tipo</label>
                                                <input type="text" name="tipo" class="form-control" value="<?= $v['tipo'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Capacidad de Carga</label>
                                                <input type="text" name="capacidad_carga" class="form-control" value="<?= $v['capacidad_carga'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Estado *</label>
                                                <select name="estado" class="form-control" required>
                                                    <option value="Disponible" <?= $v['estado'] === 'Disponible' ? 'selected' : '' ?>>Disponible</option>
                                                    <option value="En Uso" <?= $v['estado'] === 'En Uso' ? 'selected' : '' ?>>En Uso</option>
                                                    <option value="En Mantenimiento" <?= $v['estado'] === 'En Mantenimiento' ? 'selected' : '' ?>>En Mantenimiento</option>
                                                    <option value="Inactivo" <?= $v['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Observaciones</label>
                                                <textarea name="observacion" class="form-control" rows="2"><?= $v['observacion'] ?? '' ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Actualizar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: CREAR VEHÍCULO -->
<!-- ========================================== -->
<div class="modal fade" id="modalCrearVehiculo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= BASE_URL ?>logistica/crearVehiculo">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-car"></i> Nuevo Vehículo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Placa *</label>
                        <input type="text" name="placa" class="form-control" placeholder="ABC-123" required>
                    </div>
                    <div class="form-group">
                        <label>Marca *</label>
                        <input type="text" name="marca" class="form-control" placeholder="Toyota" required>
                    </div>
                    <div class="form-group">
                        <label>Modelo *</label>
                        <input type="text" name="modelo" class="form-control" placeholder="Hilux" required>
                    </div>
                    <div class="form-group">
                        <label>Año</label>
                        <input type="number" name="anio" class="form-control" placeholder="2020">
                    </div>
                    <div class="form-group">
                        <label>Color</label>
                        <input type="text" name="color" class="form-control" placeholder="Blanco">
                    </div>
                    <div class="form-group">
                        <label>Tipo</label>
                        <input type="text" name="tipo" class="form-control" placeholder="Camioneta">
                    </div>
                    <div class="form-group">
                        <label>Capacidad de Carga</label>
                        <input type="text" name="capacidad_carga" class="form-control" placeholder="1.5 TN">
                    </div>
                    <div class="form-group">
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            <option value="Disponible">Disponible</option>
                            <option value="En Uso">En Uso</option>
                            <option value="En Mantenimiento">En Mantenimiento</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea name="observacion" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-group { margin-bottom: 15px; }
.form-group label { font-weight: 600; font-size: 13px; color: #0f172a; display: block; margin-bottom: 4px; }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>



