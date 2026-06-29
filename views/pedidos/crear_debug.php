<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h4>DEBUG - Nueva Nota de Pedido</h4>
    </div>
    <div class="card-body">
        <!-- Debug: Mostrar productos -->
        <div class="alert alert-info">
            <strong>🔍 Productos en la vista:</strong>
            <?php if (empty($productos)): ?>
                <span class="text-danger">⚠️ NO HAY PRODUCTOS</span>
            <?php else: ?>
                <span class="text-success">✅ <?= count($productos) ?> productos encontrados</span>
                <ul>
                    <?php foreach ($productos as $p): ?>
                        <li><?= $p['codigo'] ?> - <?= $p['nombre'] ?> - S/ <?= $p['precio'] ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        
        <hr>
        
        <p>Total productos en variable: <?= count($productos) ?></p>
        
        <!-- Script para debug -->
        <script>
            var productosData = <?= json_encode($productos) ?>;
            console.log('🔍 productosData desde PHP:', productosData);
            console.log('📦 Cantidad:', productosData.length);
        </script>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
