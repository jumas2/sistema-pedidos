<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-users"></i></span>
            Gestión de Transportistas
        </h1>
        <p class="page-subtitle">Administración de conductores/choferes y asignación de vehículos</p>
    </div>
    <div class="header-right">
        <button type="button" class="btn-primary-modern" data-bs-toggle="modal" data-bs-target="#modalCrearTransportista">
            <i class="fas fa-plus"></i> Nuevo Transportista
        </button>
        <a href="<?= BASE_URL ?>?url=logistica" class="btn-secondary-modern">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-info text-white">
        <i class="fas fa-list"></i> Lista de Transportistas
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>DNI</th>
                        <th>Teléfono</th>
                        <th>Licencia</th>
                        <th>Vehículo Asignado</th>
                        <th>Usuario Asociado</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transportistas as $t): ?>
                        <tr>
                            <td><strong><?= $t['nombre'] . ' ' . $t['apellido'] ?></strong></td>
                            <td><?= $t['dni'] ?></td>
                            <td><?= $t['telefono'] ?></td>
                            <td><?= $t['licencia'] ?></td>
                            <td>
                                <?php if (!empty($t['vehiculo_placa'])): ?>
                                    <?= $t['vehiculo_marca'] . ' ' . $t['vehiculo_modelo'] . ' (' . $t['vehiculo_placa'] . ')' ?>
                                    <br>
                                    <span class="badge <?= $t['tipo_asignacion_vehiculo'] === 'permanente' ? 'bg-success' : 'bg-warning' ?>">
                                        <?= $t['tipo_asignacion_vehiculo'] ?? 'temporal' ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">Sin asignar</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($t['usuario_id'])): ?>
                                    <?php 
                                        $usuarioModel = new Usuario();
                                        $usuario = $usuarioModel->find($t['usuario_id']);
                                        echo $usuario['nombre'] ?? 'N/A';
                                    ?>
                                    <br>
                                    <small class="text-muted"><?= $usuario['email'] ?? '' ?></small>
                                <?php else: ?>
                                    <span class="text-muted">Sin asociar</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= $t['activo'] ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $t['activo'] ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </td>
                            <td>
                                <?php if (empty($t['vehiculo_placa']) && $t['activo']): ?>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAsignarVehiculo<?= $t['id'] ?>">
                                        <i class="fas fa-car"></i> Asignar
                                    </button>
                                <?php elseif (!empty($t['vehiculo_placa']) && $t['activo']): ?>
                                    <a href="<?= BASE_URL ?>?url=logistica/liberarVehiculoTransportista/<?= $t['id'] ?>" class="btn btn-sm btn-warning" onclick="return confirm('¿Estás seguro de liberar el vehículo?')">
                                        <i class="fas fa-times"></i> Liberar
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (!$t['activo']): ?>
                                    <a href="<?= BASE_URL ?>?url=logistica/reactivarTransportista/<?= $t['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('¿Reactivar este transportista?')">
                                        <i class="fas fa-check"></i> Reactivar
                                    </a>
                                <?php endif; ?>
                                
                                <a href="<?= BASE_URL ?>?url=logistica/historialVehiculos/<?= $t['id'] ?>" class="btn btn-sm btn-info" title="Ver historial">
                                    <i class="fas fa-history"></i>
                                </a>
                                
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditarTransportista<?= $t['id'] ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <?php if ($t['activo']): ?>
                                    <a href="<?= BASE_URL ?>?url=logistica/eliminarTransportista/<?= $t['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este transportista?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        
                        <!-- Modal Asignar Vehículo -->
                        <div class="modal fade" id="modalAsignarVehiculo<?= $t['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="<?= BASE_URL ?>?url=logistica/asignarVehiculoTransportista">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title"><i class="fas fa-car"></i> Asignar Vehículo</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="transportista_id" value="<?= $t['id'] ?>">
                                            <div class="form-group">
                                                <label>Vehículo *</label>
                                                <select name="vehiculo_id" class="form-control" required>
                                                    <option value="">Seleccionar vehículo disponible</option>
                                                    <?php foreach ($vehiculos as $v): ?>
                                                        <?php if ($v['estado'] === 'Disponible'): ?>
                                                            <option value="<?= $v['id'] ?>"><?= $v['placa'] . ' - ' . $v['marca'] . ' ' . $v['modelo'] ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Tipo de Asignación</label>
                                                <select name="tipo_asignacion" class="form-control">
                                                    <option value="temporal">Temporal (solo para este servicio)</option>
                                                    <option value="permanente">Permanente (fijo)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Asignar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Modal Editar -->
                        <div class="modal fade" id="modalEditarTransportista<?= $t['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="<?= BASE_URL ?>?url=logistica/editarTransportista/<?= $t['id'] ?>">
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Transportista</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Nombre *</label>
                                                <input type="text" name="nombre" class="form-control" value="<?= $t['nombre'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Apellido *</label>
                                                <input type="text" name="apellido" class="form-control" value="<?= $t['apellido'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>DNI *</label>
                                                <input type="text" name="dni" class="form-control" value="<?= $t['dni'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Teléfono</label>
                                                <input type="text" name="telefono" class="form-control" value="<?= $t['telefono'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Licencia</label>
                                                <input type="text" name="licencia" class="form-control" value="<?= $t['licencia'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Usuario Asociado</label>
                                                <select name="usuario_id" class="form-control">
                                                    <option value="">Sin asociar</option>
                                                    <?php foreach ($usuariosDisponibles as $u): ?>
                                                        <option value="<?= $u['id'] ?>" <?= ($t['usuario_id'] == $u['id']) ? 'selected' : '' ?>>
                                                            <?= $u['nombre'] ?> (<?= $u['email'] ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Estado</label>
                                                <select name="activo" class="form-control">
                                                    <option value="1" <?= $t['activo'] ? 'selected' : '' ?>>Activo</option>
                                                    <option value="0" <?= !$t['activo'] ? 'selected' : '' ?>>Inactivo</option>
                                                </select>
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
<!-- MODAL: CREAR TRANSPORTISTA -->
<!-- ========================================== -->
<div class="modal fade" id="modalCrearTransportista" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= BASE_URL ?>?url=logistica/crearTransportista" id="formCrearTransportista">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-user-plus"></i> Nuevo Transportista</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Crear desde Usuario Existente</label>
                        <select name="usuario_id" class="form-control" id="usuarioSelect">
                            <option value="">Seleccionar usuario de logística/conductor</option>
                            <?php foreach ($usuariosDisponibles as $u): ?>
                                <option value="<?= $u['id'] ?>" 
                                    data-nombre="<?= $u['nombre'] ?>" 
                                    data-dni="<?= $u['dni'] ?? '' ?>" 
                                    data-telefono="<?= $u['telefono'] ?? '' ?>">
                                    <?= $u['nombre'] ?> (<?= $u['email'] ?>) - <?= $u['rol_nombre'] ?? 'Sin rol' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Selecciona un usuario para autocompletar sus datos (puedes editarlos si lo necesitas).</small>
                    </div>
                    
                    <hr>
                    <p class="text-muted text-center">Datos del Transportista</p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre *</label>
                                <input type="text" name="nombre" id="inputNombre" class="form-control" placeholder="Juan" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Apellido *</label>
                                <input type="text" name="apellido" id="inputApellido" class="form-control" placeholder="Pérez" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>DNI *</label>
                                <input type="text" name="dni" id="inputDni" class="form-control" placeholder="8 digitos #" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" name="telefono" id="inputTelefono" class="form-control" placeholder="999999999">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Licencia / Brevete *</label>
                                <input type="text" name="licencia" id="inputLicencia" class="form-control" placeholder="Ej: A1, A2B" required>
                                <small class="text-muted">Siempre habilitado para completar.</small>
                            </div>
                        </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    var usuarioSelect = document.getElementById('usuarioSelect');
    if (usuarioSelect) {
        usuarioSelect.addEventListener('change', function() {
            var selected = this.options[this.selectedIndex];
            var nombre = selected.getAttribute('data-nombre');
            var dni = selected.getAttribute('data-dni');
            var telefono = selected.getAttribute('data-telefono');
            
            var inputNombre = document.getElementById('inputNombre');
            var inputApellido = document.getElementById('inputApellido');
            var inputDni = document.getElementById('inputDni');
            var inputTelefono = document.getElementById('inputTelefono');
            var inputLicencia = document.getElementById('inputLicencia');
            
            if (selected.value) {
                var partes = nombre.split(' ', 2);
                var nombreParte = partes[0] || '';
                var apellidoParte = partes[1] || '';
                
                inputNombre.value = nombreParte;
                inputNombre.placeholder = 'Nombre (puedes editarlo)';
                
                inputApellido.value = apellidoParte;
                inputApellido.placeholder = 'Apellido (puedes editarlo)';
                
                inputDni.value = dni || '';
                inputDni.placeholder = 'DNI (puedes editarlo)';
                
                inputTelefono.value = telefono || '';
                inputTelefono.placeholder = 'Teléfono (puedes editarlo)';
                
                inputLicencia.placeholder = 'Ingresa el número de licencia/brevete';
                inputLicencia.focus();
            } else {
                inputNombre.value = '';
                inputApellido.value = '';
                inputDni.value = '';
                inputTelefono.value = '';
                inputLicencia.value = '';
                
                inputNombre.placeholder = 'Juan';
                inputApellido.placeholder = 'Pérez';
                inputDni.placeholder = '12345678';
                inputTelefono.placeholder = '999999999';
                inputLicencia.placeholder = 'Ej: A1, A2B';
            }
        });
    }
});
</script>

<style>
.form-group { margin-bottom: 15px; }
.form-group label { font-weight: 600; font-size: 13px; color: #0f172a; display: block; margin-bottom: 4px; }
hr { margin: 15px 0; }
.text-muted { color: #64748b; }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
