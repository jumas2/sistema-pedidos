<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-plus-circle"></i></span>
            Nuevo Pedido
        </h1>
        <p class="page-subtitle">Crear una nueva nota de pedido</p>
    </div>
    <div class="header-right">
        <a href="<?= BASE_URL ?>pedidos" class="btn-secondary-modern">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<form id="formPedido" method="POST" action="<?= BASE_URL ?>pedidos/guardar">

    <!-- ========================================== -->
    <!-- CABECERA + MONEDA -->
    <!-- ========================================== -->
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-file-invoice"></i> Datos del Pedido
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>N° Pedido</label>
                        <input type="text" class="form-control" name="numero_pedido" value="<?= $numero_pedido ?>" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Cliente *</label>
                        <select class="form-control" name="cliente_id" id="cliente_id" required>
                            <option value="">Seleccionar cliente</option>
                            <?php foreach ($clientes as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>RUC/DNI</label>
                        <input type="text" class="form-control" id="ruc_dni" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" class="form-control" id="telefono" readonly>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Dirección Fiscal</label>
                        <input type="text" class="form-control" id="direccion_fiscal" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Orden de Compra (OC)</label>
                        <input type="text" class="form-control" name="oc" placeholder="Opcional">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Condición *</label>
                        <select class="form-control" name="condicion" required>
                            <option value="Boleta-Contado">Boleta - Contado</option>
                            <option value="Boleta-Crédito">Boleta - Crédito</option>
                            <option value="Factura-Contado">Factura - Contado</option>
                            <option value="Factura-Crédito">Factura - Crédito</option>
                            <option value="Muestra">Muestra</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha Pedido</label>
                        <input type="date" class="form-control" name="fecha_pedido" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Vendedor</label>
                        <select class="form-control" name="vendedor_id">
                            <option value="">Seleccionar</option>
                            <?php foreach ($vendedores as $v): ?>
                                <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Zona (Distrito)</label>
                        <input type="text" class="form-control" name="zona" placeholder="Ej: VMT - SURCO - MIRAFLORES">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Fecha Entrega</label>
                        <input type="date" class="form-control" name="fecha_entrega">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Moneda del Pedido *</label>
                        <select class="form-control" name="moneda" id="monedaSelect" required>
                            <option value="PEN">Soles (S/)</option>
                            <option value="USD">Dólares ($)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- PRODUCTOS -->
    <!-- ========================================== -->
    <div class="card mb-3">
        <div class="card-header bg-success text-white d-flex justify-content-between">
            <span><i class="fas fa-boxes"></i> Productos</span>
            <span class="badge bg-light text-dark"><?= count($productos) ?> disponibles</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tablaProductos">
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
            <button type="button" class="btn btn-info" id="agregarFila">
                <i class="fas fa-plus"></i> Agregar Producto
            </button>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- ENVÍO Y DESPACHO -->
    <!-- ========================================== -->
    <div class="card mb-3">
        <div class="card-header bg-warning text-dark">
            <i class="fas fa-truck"></i> Envío y Despacho
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Agencia Transporte</label>
                        <select class="form-control" name="agencia_id">
                            <option value="">Seleccionar</option>
                            <?php foreach ($agencias as $a): ?>
                                <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Dirección Envío</label>
                        <input type="text" class="form-control" name="direccion_envio">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>A nombre de</label>
                        <input type="text" class="form-control" name="nombre_recibe">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>DNI</label>
                        <input type="text" class="form-control" name="dni_recibe">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Observación</label>
                        <textarea class="form-control" name="observacion" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="productos" id="productos_json">

    <div class="text-center">
        <button type="submit" class="btn-primary-modern" onclick="return guardarPedido()">
            <i class="fas fa-save"></i> Guardar Pedido
        </button>
        <a href="<?= BASE_URL ?>pedidos" class="btn-secondary-modern">Cancelar</a>
    </div>
</form>

<script>
var productosData = <?= json_encode($productos) ?>;
var BASE_URL = '<?= BASE_URL ?>';

// ============================================
// AGREGAR FILA
// ============================================
function agregarFilaProducto() {
    if (!productosData || productosData.length === 0) {
        Swal.fire('Error', 'No hay productos disponibles', 'error');
        return;
    }

    var tbody = document.getElementById('productosBody');
    if (!tbody) return;

    var options = '<option value="">Seleccionar...</option>';
    for (var i = 0; i < productosData.length; i++) {
        var p = productosData[i];
        options += '<option value="' + p.id + '" data-codigo="' + p.codigo + '" data-precio="' + p.precio + '">'
            + p.codigo + ' - ' + p.nombre
            + '</option>';
    }

    var row = document.createElement('tr');
    row.innerHTML = `
        <td><input type="text" class="form-control codigo" placeholder="Código" readonly></td>
        <td><select class="form-control producto-select">${options}</select></td>
        <td><input type="number" class="form-control cantidad" value="1" min="1"></td>
        <td><input type="number" class="form-control precio_unitario" step="0.01"></td>
        <td><input type="text" class="form-control monto" readonly value="0.00"></td>
        <td><button type="button" class="btn btn-danger btn-sm eliminar-fila"><i class="fas fa-times"></i></button></td>
    `;
    tbody.appendChild(row);
    calcularTotales();
}

// ============================================
// CÁLCULOS
// ============================================
function calcularFila(row) {
    var cantidad = parseFloat(row.querySelector('.cantidad').value) || 0;
    var precio = parseFloat(row.querySelector('.precio_unitario').value) || 0;
    row.querySelector('.monto').value = (cantidad * precio).toFixed(2);
    calcularTotales();
}

function calcularTotales() {
    var subtotal = 0;
    var rows = document.querySelectorAll('#productosBody tr');
    for (var i = 0; i < rows.length; i++) {
        var monto = parseFloat(rows[i].querySelector('.monto').value) || 0;
        subtotal += monto;
    }
    var igv = subtotal * 0.18;
    var total = subtotal + igv;
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('igv').value = igv.toFixed(2);
    document.getElementById('total').value = total.toFixed(2);
}

// ============================================
// GUARDAR
// ============================================
function guardarPedido() {
    var productos = [];
    var rows = document.querySelectorAll('#productosBody tr');
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var select = row.querySelector('.producto-select');
        var cantidad = row.querySelector('.cantidad');
        var precio = row.querySelector('.precio_unitario');
        if (!select || !cantidad || !precio) {
            Swal.fire('Error', 'Fila ' + (i + 1) + ' incompleta', 'error');
            return false;
        }
        if (!select.value || !cantidad.value || !precio.value) {
            Swal.fire('Error', 'Complete la fila ' + (i + 1), 'error');
            return false;
        }
        productos.push({
            producto_id: select.value,
            cantidad: cantidad.value,
            precio_unitario: precio.value
        });
    }
    if (productos.length === 0) {
        Swal.fire('Error', 'Agregue al menos un producto', 'error');
        return false;
    }
    document.getElementById('productos_json').value = JSON.stringify(productos);
    return true;
}

// ============================================
// EVENTOS
// ============================================
$(document).ready(function() {
    // Auto-completar cliente
    $('#cliente_id').on('change', function() {
        var id = this.value;
        if (id) {
            $.ajax({
                url: BASE_URL + 'clientes/obtener/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#ruc_dni').val(data.ruc_dni || '');
                    $('#direccion_fiscal').val(data.direccion_fiscal || '');
                    $('#telefono').val(data.telefono || '');
                }
            });
        }
    });

    // Agregar fila
    $('#agregarFila').on('click', function() {
        agregarFilaProducto();
    });

    // Eliminar fila
    $(document).on('click', '.eliminar-fila', function() {
        var row = $(this).closest('tr');
        var tbody = document.getElementById('productosBody');
        if (tbody && tbody.querySelectorAll('tr').length > 1) {
            row.remove();
            calcularTotales();
        } else {
            Swal.fire('Error', 'Debe haber al menos un producto', 'warning');
        }
    });

    // Seleccionar producto
    $(document).on('change', '.producto-select', function() {
        var select = this;
        var row = $(select).closest('tr');
        var option = select.options[select.selectedIndex];
        row.find('.codigo').val(option.getAttribute('data-codigo') || '');
        row.find('.precio_unitario').val(option.getAttribute('data-precio') || 0);
        calcularFila(row[0]);
    });

    // Calcular
    $(document).on('keyup change', '.cantidad, .precio_unitario', function() {
        var row = $(this).closest('tr')[0];
        calcularFila(row);
    });

    // Primera fila
    if (productosData && productosData.length > 0) {
        agregarFilaProducto();
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
