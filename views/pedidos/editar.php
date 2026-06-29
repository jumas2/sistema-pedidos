<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="card">
    <div class="card-header bg-warning text-dark">
        <h4><i class="fas fa-edit"></i> Editar Nota de Pedido N° <?= $pedidoData['numero_pedido'] ?></h4>
    </div>
    <div class="card-body">
        <form id="formPedido" method="POST" action="<?= BASE_URL ?>pedidos/actualizar/<?= $pedidoData['id'] ?>">
            
            <div class="row mb-3">
                <div class="col-md-2">
                    <label class="form-label">N° Pedido</label>
                    <input type="text" class="form-control" name="numero_pedido" value="<?= $pedidoData['numero_pedido'] ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cliente *</label>
                    <select class="form-select" name="cliente_id" id="cliente_id" required>
                        <option value="">Seleccionar cliente</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= $c['id'] == $pedidoData['cliente_id'] ? 'selected' : '' ?>><?= $c['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">RUC/DNI</label>
                    <input type="text" class="form-control" id="ruc_dni" value="<?= $pedidoData['ruc_dni'] ?? '' ?>" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion_fiscal" value="<?= $pedidoData['direccion_fiscal'] ?? '' ?>" readonly>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Orden de Compra (OC)</label>
                    <input type="text" class="form-control" name="oc" value="<?= $pedidoData['oc'] ?? '' ?>" placeholder="Opcional">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Condición *</label>
                    <select class="form-select" name="condicion" required>
                        <option value="Boleta-Contado" <?= $pedidoData['condicion'] == 'Boleta-Contado' ? 'selected' : '' ?>>Boleta - Contado</option>
                        <option value="Boleta-Crédito" <?= $pedidoData['condicion'] == 'Boleta-Crédito' ? 'selected' : '' ?>>Boleta - Crédito</option>
                        <option value="Factura-Contado" <?= $pedidoData['condicion'] == 'Factura-Contado' ? 'selected' : '' ?>>Factura - Contado</option>
                        <option value="Factura-Crédito" <?= $pedidoData['condicion'] == 'Factura-Crédito' ? 'selected' : '' ?>>Factura - Crédito</option>
                        <option value="Muestra" <?= $pedidoData['condicion'] == 'Muestra' ? 'selected' : '' ?>>Muestra</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha Pedido</label>
                    <input type="date" class="form-control" name="fecha_pedido" value="<?= $pedidoData['fecha_pedido'] ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Vendedor</label>
                    <select class="form-select" name="vendedor_id">
                        <option value="">Seleccionar</option>
                        <?php foreach ($vendedores as $v): ?>
                            <option value="<?= $v['id'] ?>" <?= $v['id'] == $pedidoData['vendedor_id'] ? 'selected' : '' ?>><?= $v['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Zona (Distrito)</label>
                    <input type="text" class="form-control" name="zona" value="<?= $pedidoData['zona'] ?? '' ?>" placeholder="Ej: VMT - SURCO - MIRAFLORES">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha Entrega</label>
                    <input type="date" class="form-control" name="fecha_entrega" value="<?= $pedidoData['fecha_entrega'] ?? '' ?>">
                </div>
            </div>
            
            <hr>
            
            <h5><i class="fas fa-box"></i> Productos en el Pedido</h5>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tablaEditarPedidos">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <th>Monto</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="productosBody">
                        </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Subtotal</strong></td>
                            <td><input type="text" class="form-control" id="subtotal" readonly value="0.00"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>IGV (18%)</strong></td>
                            <td><input type="text" class="form-control" id="igv" readonly value="0.00"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total</strong></td>
                            <td><input type="text" class="form-control" id="total" readonly value="0.00"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <button type="button" class="btn btn-info text-white" id="btnAgregarFilaEditar">
                <i class="fas fa-plus"></i> Agregar Producto
            </button>
            
            <hr>
            
            <h5><i class="fas fa-truck"></i> Datos de Envío</h5>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Agencia Transporte</label>
                    <select class="form-select" name="agencia_id">
                        <option value="">Seleccionar</option>
                        <?php foreach ($agencias as $a): ?>
                            <option value="<?= $a['id'] ?>" <?= $a['id'] == $pedidoData['agencia_id'] ? 'selected' : '' ?>><?= $a['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Dirección Envío</label>
                    <input type="text" class="form-control" name="direccion_envio" value="<?= $pedidoData['direccion_envio'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">A nombre de</label>
                    <input type="text" class="form-control" name="nombre_recibe" value="<?= $pedidoData['nombre_recibe'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">DNI</label>
                    <input type="text" class="form-control" name="dni_recibe" value="<?= $pedidoData['dni_recibe'] ?? '' ?>">
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Observación</label>
                    <textarea class="form-control" name="observacion" rows="2"><?= $pedidoData['observacion'] ?? '' ?></textarea>
                </div>
            </div>
            
            <input type="hidden" name="productos" id="productos_json">
            
            <div class="text-center">
                <button type="submit" class="btn btn-warning btn-lg text-dark" id="btnGuardar">
                    <i class="fas fa-sync"></i> Actualizar Pedido
                </button>
                <a href="<?= BASE_URL ?>pedidos" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
// Catálogo completo enviado desde el controlador
var productosData = <?= json_encode($productos) ?>;
// Detalles actuales que ya pertenecen a esta Nota de Pedido
var detallesExistentes = <?= json_encode($detalles) ?>;
var BASE_URL = '<?= BASE_URL ?>';

console.log('📦 Catálogo de productos listo:', productosData);
console.log('📋 Detalles cargados para edición:', detallesExistentes);

function agregarFilaProducto(datosPrendidos = null) {
    if (!productosData || productosData.length === 0) {
        Swal.fire('Error', 'No hay productos disponibles en el catálogo.', 'error');
        return;
    }
    
    var tbody = document.getElementById('productosBody');
    if (!tbody) return;
    
    var options = '<option value="">Seleccionar producto...</option>';
    for (var i = 0; i < productosData.length; i++) {
        var p = productosData[i];
        var selected = (datosPrendidos && datosPrendidos.producto_id == p.id) ? 'selected' : '';
        options += '<option value="' + p.id + '" data-codigo="' + p.codigo + '" data-precio="' + p.precio + '" ' + selected + '>' + p.codigo + ' - ' + p.nombre + '</option>';
    }
    
    var row = document.createElement('tr');
    row.className = 'fila-producto';
    
    var initCodigo = datosPrendidos ? datosPrendidos.codigo : '';
    var initCantidad = datosPrendidos ? datosPrendidos.cantidad : 1;
    var initPrecio = datosPrendidos ? datosPrendidos.precio_unitario : '';
    var initMonto = datosPrendidos ? (parseFloat(initCantidad) * parseFloat(initPrecio)).toFixed(2) : '0.00';
    
    row.innerHTML = `
        <td><input type="text" class="form-control codigo" value="${initCodigo}" placeholder="Código" readonly></td>
        <td>
            <select class="form-select producto-select" required>
                ${options}
            </select>
        </td>
        <td><input type="number" class="form-control cantidad" value="${initCantidad}" min="1" required></td>
        <td><input type="number" class="form-control precio_unitario" value="${initPrecio}" step="0.01" required></td>
        <td><input type="text" class="form-control monto" readonly value="${initMonto}"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm eliminar-fila">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    calcularTotales();
}

function calcularTotales() {
    var subtotal = 0;
    var rows = document.querySelectorAll('#productosBody tr');
    for (var i = 0; i < rows.length; i++) {
        var montoInput = rows[i].querySelector('.monto');
        if (montoInput) {
            subtotal += parseFloat(montoInput.value) || 0;
        }
    }
    var igv = subtotal * 0.18;
    var total = subtotal + igv;
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('igv').value = igv.toFixed(2);
    document.getElementById('total').value = total.toFixed(2);
}

function calcularFila(row) {
    var cantidad = parseFloat(row.querySelector('.cantidad').value) || 0;
    var precio = parseFloat(row.querySelector('.precio_unitario').value) || 0;
    row.querySelector('.monto').value = (cantidad * precio).toFixed(2);
    calcularTotales();
}

function guardarPedido() {
    var productos = [];
    var rows = document.querySelectorAll('#productosBody tr');
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var select = row.querySelector('.producto-select');
        var cantidad = row.querySelector('.cantidad');
        var precio = row.querySelector('.precio_unitario');
        
        if (!select || !cantidad || !precio) return false;
        if (!select.value || !cantidad.value || !precio.value) {
            Swal.fire('Error', 'Complete la información de la fila ' + (i + 1), 'error');
            return false;
        }
        productos.push({
            producto_id: select.value,
            cantidad: cantidad.value,
            precio_unitario: precio.value
        });
    }
    if (productos.length === 0) {
        Swal.fire('Error', 'Debe mantener al menos un producto en la nota de pedido', 'error');
        return false;
    }
    document.getElementById('productos_json').value = JSON.stringify(productos);
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    // 1. Cargar las filas existentes que ya venían guardadas en la BD
    if (detallesExistentes && detallesExistentes.length > 0) {
        for (var i = 0; i < detallesExistentes.length; i++) {
            agregarFilaProducto(detallesExistentes[i]);
        }
    } else {
        // Si por alguna razón vino sin filas, inicializar con una vacía
        agregarFilaProducto();
    }
    
    // 2. Evento aislado del botón de agregar filas en edición
    document.getElementById('btnAgregarFilaEditar').addEventListener('click', function(e) {
        e.preventDefault();
        agregarFilaProducto();
    });
    
    document.getElementById('cliente_id').addEventListener('change', function() {
        var id = this.value;
        if (id) {
            fetch(BASE_URL + 'clientes/obtener/' + id)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('ruc_dni').value = data.ruc_dni || '';
                    document.getElementById('direccion_fiscal').value = data.direccion_fiscal || '';
                })
                .catch(err => console.error(err));
        }
    });
    
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.eliminar-fila');
        if (btn) {
            var row = btn.closest('tr');
            var tbody = document.getElementById('productosBody');
            if (tbody && tbody.querySelectorAll('tr').length > 1) {
                row.remove();
                calcularTotales();
            } else {
                Swal.fire('Error', 'Es obligatorio mantener un producto en el pedido', 'warning');
            }
        }
    });
    
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('producto-select')) {
            var select = e.target;
            var row = select.closest('tr');
            var option = select.options[select.selectedIndex];
            row.querySelector('.codigo').value = option.getAttribute('data-codigo') || '';
            row.querySelector('.precio_unitario').value = option.getAttribute('data-precio') || 0;
            calcularFila(row);
        }
    });
    
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('cantidad') || e.target.classList.contains('precio_unitario')) {
            var row = e.target.closest('tr');
            if (row) calcularFila(row);
        }
    });
    
    document.getElementById('formPedido').addEventListener('submit', function(e) {
        if (!guardarPedido()) {
            e.preventDefault();
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
