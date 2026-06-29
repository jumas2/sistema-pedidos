<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- ========================================== -->
<!-- HEADER -->
<!-- ========================================== -->
<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">
            <span class="title-icon primary"><i class="fas fa-file-invoice"></i></span>
            Lista de Pedidos
        </h1>
        <p class="page-subtitle">
            Gestión de notas de pedido
            <?php if (isset($_GET['estado'])): ?>
                <span class="badge" style="background: #4f46e5; color: white; margin-left: 8px;">
                    Filtro: <?= $_GET['estado'] ?>
                    <a href="<?= BASE_URL ?>pedidos" style="color: white; text-decoration: none; margin-left: 4px;">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
            <?php endif; ?>
        </p>
    </div>
    <div class="header-right">
        <a href="<?= BASE_URL ?>pedidos/crear" class="btn-primary-modern">
            <i class="fas fa-plus"></i> Nuevo Pedido
        </a>
    </div>
</div>

<!-- ========================================== -->
<!-- TABLA DE PEDIDOS -->
<!-- ========================================== -->
<div class="table-container">
    <div class="table-toolbar">
        <div class="toolbar-left">
            <span class="total-registros">
                <i class="fas fa-database"></i> <?= count($pedidos) ?> registros
            </span>
        </div>
        <div class="toolbar-right">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="buscarPedido" class="search-input" placeholder="Buscar pedido...">
            </div>
        </div>
    </div>

    <div class="table-responsive-modern">
        <table class="table-modern" id="tablaPedidos">
            <thead>
                <tr>
                    <th class="col-numero">N° Pedido</th>
                    <th class="col-cliente">Cliente</th>
                    <th class="col-fecha">Fecha</th>
                    <th class="col-total">Total</th>
                    <th class="col-estado">Estado</th>
                    <th class="col-acciones">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $p): ?>
                    <tr class="fila-pedido">
                        <td class="col-numero">
                            <span class="numero-pedido tooltip-trigger" 
                                  data-pedido-id="<?= $p['id'] ?>"
                                  style="cursor:pointer; text-decoration:underline dotted; color:#4f46e5;">
                                <i class="fas fa-hashtag"></i>
                                <?= $p['numero_pedido'] ?>
                            </span>
                        </td>
                        <td class="col-cliente">
                            <div class="cliente-info">
                                <div class="cliente-avatar">
                                    <?php 
                                        $iniciales = '';
                                        $nombre = $p['cliente_nombre'] ?? '';
                                        $palabras = explode(' ', $nombre);
                                        foreach ($palabras as $palabra) {
                                            if (!empty($palabra)) {
                                                $iniciales .= strtoupper($palabra[0]);
                                            }
                                        }
                                        $iniciales = substr($iniciales, 0, 2);
                                    ?>
                                    <span class="avatar-text"><?= $iniciales ?: '?' ?></span>
                                </div>
                                <div class="cliente-detalle">
                                    <span class="cliente-nombre"><?= $p['cliente_nombre'] ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="col-fecha">
                            <div class="fecha-info">
                                <i class="far fa-calendar-alt"></i>
                                <span><?= date('d/m/Y', strtotime($p['fecha_pedido'])) ?></span>
                            </div>
                        </td>
                        <td class="col-total">
                            <span class="total-monto">S/ <?= number_format($p['total'], 2) ?></span>
                        </td>
                        <td class="col-estado">
                            <?php 
                                $estados = [
                                    'Pendiente' => ['label' => 'Pendiente', 'icon' => 'fa-clock', 'class' => 'estado-pendiente'],
                                    'Atendido'  => ['label' => 'Atendido', 'icon' => 'fa-check-circle', 'class' => 'estado-atendido'],
                                    'Anulado'   => ['label' => 'Anulado', 'icon' => 'fa-ban', 'class' => 'estado-anulado']
                                ];
                                $e = $estados[$p['estado']] ?? ['label' => $p['estado'], 'icon' => 'fa-question', 'class' => 'estado-unknown'];
                            ?>
                            <span class="estado-badge <?= $e['class'] ?>">
                                <i class="fas <?= $e['icon'] ?>"></i>
                                <?= $e['label'] ?>
                            </span>
                        </td>
                        <td class="col-acciones">
                            <div class="acciones-group">
                                <a href="<?= BASE_URL ?>pedidos/ver/<?= $p['id'] ?>" class="btn-action btn-view" title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($p['estado'] === 'Pendiente'): ?>
                                    <a href="<?= BASE_URL ?>pedidos/editar/<?= $p['id'] ?>" class="btn-action btn-edit" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= BASE_URL ?>pedidos/duplicar/<?= $p['id'] ?>" class="btn-action btn-duplicar" title="Duplicar">
                                    <i class="fas fa-copy"></i>
                                </a>
                                <?php if (tieneRol('admin') && $p['estado'] !== 'Atendido'): ?>
                                    <a href="<?= BASE_URL ?>pedidos/eliminar/<?= $p['id'] ?>" class="btn-action btn-delete" onclick="return confirm('¿Eliminar este pedido?')" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                
                <?php if (empty($pedidos)): ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <div class="empty-content">
                                <i class="fas fa-inbox"></i>
                                <p><?= isset($_GET['estado']) ? 'No hay pedidos ' . strtolower($_GET['estado']) : 'No hay pedidos registrados' ?></p>
                                <a href="<?= BASE_URL ?>pedidos/crear" class="btn-empty-action">
                                    <i class="fas fa-plus"></i> Crear primer pedido
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================== -->
<!-- TOOLTIP -->
<!-- ========================================== -->
<div id="tooltipCard" style="display:none; position:fixed; z-index:9999; background:white; border-radius:12px; box-shadow:0 10px 40px rgba(0,0,0,0.2); width:380px; max-height:450px; overflow-y:auto; padding:0; font-size:13px; border:1px solid #e2e8f0; pointer-events:none;">
    <div style="padding:16px 20px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; background:#fafbfc; border-radius:12px 12px 0 0;">
        <span style="font-weight:700; color:#0f172a; font-size:14px;" id="tooltipNumero">NP-2026-0000</span>
        <span id="tooltipEstado" class="estado-badge estado-pendiente" style="font-size:11px; padding:3px 12px;">Pendiente</span>
    </div>
    <div style="padding:16px 20px;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px 16px; margin-bottom:12px; font-size:12px;">
            <div><span style="color:#64748b;">Cliente:</span> <strong id="tooltipCliente">-</strong></div>
            <div><span style="color:#64748b;">RUC:</span> <span id="tooltipRuc">-</span></div>
            <div><span style="color:#64748b;">Fecha:</span> <span id="tooltipFecha">-</span></div>
            <div><span style="color:#64748b;">Condición:</span> <span id="tooltipCondicion">-</span></div>
            <div style="grid-column: span 2;"><span style="color:#64748b;">Zona:</span> <span id="tooltipZona">-</span></div>
        </div>
        <div style="border-top:1px solid #e2e8f0; padding-top:10px;">
            <div style="font-weight:600; font-size:12px; color:#64748b; margin-bottom:6px;">ITEMS</div>
            <div id="tooltipItems" style="font-size:12px;"></div>
        </div>
        <div style="border-top:1px solid #e2e8f0; padding-top:10px; margin-top:8px; display:flex; justify-content:space-between; font-weight:700; font-size:15px;">
            <span>TOTAL</span>
            <span id="tooltipTotal" style="color:#4f46e5;">S/ 0.00</span>
        </div>
        <div id="tooltipObservacion" style="font-size:11px; color:#94a3b8; margin-top:6px; padding:6px 8px; background:#f8fafc; border-radius:6px; display:none;"></div>
    </div>
</div>

<!-- ========================================== -->
<!-- SCRIPTS -->
<!-- ========================================== -->
<script>
var BASE_URL = "<?= BASE_URL ?>";
document.addEventListener('DOMContentLoaded', function() {
    const tooltip = document.getElementById('tooltipCard');
    let timeoutId = null;
    let currentPedidoId = null;

    // Tooltip
    document.querySelectorAll('.tooltip-trigger').forEach(function(element) {
        element.addEventListener('mouseenter', function(e) {
            const pedidoId = this.getAttribute('data-pedido-id');
            if (pedidoId === currentPedidoId) return;
            currentPedidoId = pedidoId;
            
            tooltip.style.display = 'block';
            tooltip.querySelector('#tooltipNumero').textContent = 'Cargando...';
            tooltip.querySelector('#tooltipCliente').textContent = '...';
            tooltip.querySelector('#tooltipItems').innerHTML = '<span style="color:#94a3b8;">Cargando productos...</span>';
            
            const rect = this.getBoundingClientRect();
            let left = rect.right + 12;
            let top = rect.top - 10;
            
            if (left + 380 > window.innerWidth) {
                left = rect.left - 392;
            }
            if (top + 450 > window.innerHeight) {
                top = window.innerHeight - 460;
            }
            if (top < 10) top = 10;
            
            tooltip.style.left = left + 'px';
            tooltip.style.top = top + 'px';
            
            fetch(BASE_URL + 'pedidos/getDetalleTooltip/' + pedidoId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        tooltip.querySelector('#tooltipNumero').textContent = data.numero;
                        tooltip.querySelector('#tooltipCliente').textContent = data.cliente;
                        tooltip.querySelector('#tooltipRuc').textContent = data.ruc;
                        tooltip.querySelector('#tooltipFecha').textContent = data.fecha;
                        tooltip.querySelector('#tooltipCondicion').textContent = data.condicion;
                        tooltip.querySelector('#tooltipZona').textContent = data.zona;
                        tooltip.querySelector('#tooltipTotal').textContent = 'S/ ' + data.total;
                        
                        const estadoBadge = tooltip.querySelector('#tooltipEstado');
                        estadoBadge.textContent = data.estado;
                        estadoBadge.className = 'estado-badge';
                        if (data.estado === 'Pendiente') estadoBadge.classList.add('estado-pendiente');
                        else if (data.estado === 'Atendido') estadoBadge.classList.add('estado-atendido');
                        else if (data.estado === 'Anulado') estadoBadge.classList.add('estado-anulado');
                        
                        let itemsHtml = '';
                        data.items.forEach(function(item) {
                            itemsHtml += `<div style="display:flex; justify-content:space-between; padding:3px 0; border-bottom:1px dashed #f1f5f9;">
                                <span>${item.cantidad}x ${item.nombre}</span>
                                <span style="font-weight:500;">S/ ${item.subtotal}</span>
                            </div>`;
                        });
                        tooltip.querySelector('#tooltipItems').innerHTML = itemsHtml || '<span style="color:#94a3b8;">Sin productos</span>';
                        
                        const obsDiv = tooltip.querySelector('#tooltipObservacion');
                        if (data.observacion) {
                            obsDiv.textContent = '📝 ' + data.observacion;
                            obsDiv.style.display = 'block';
                        } else {
                            obsDiv.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error cargando tooltip:', error);
                    tooltip.querySelector('#tooltipItems').innerHTML = '<span style="color:#ef4444;">Error al cargar</span>';
                });
        });
        
        element.addEventListener('mouseleave', function(e) {
            if (timeoutId) clearTimeout(timeoutId);
            timeoutId = setTimeout(function() {
                tooltip.style.display = 'none';
                currentPedidoId = null;
            }, 300);
        });
    });
    
    tooltip.addEventListener('mouseenter', function() {
        if (timeoutId) {
            clearTimeout(timeoutId);
            timeoutId = null;
        }
    });
    
    tooltip.addEventListener('mouseleave', function() {
        tooltip.style.display = 'none';
        currentPedidoId = null;
    });

    // Búsqueda
    const searchInput = document.getElementById('buscarPedido');
    const rows = document.querySelectorAll('.fila-pedido');
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const term = this.value.toLowerCase().trim();
            
            rows.forEach(function(row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
