<button class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-bars"></i>
</button>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">P</div>
        <div class="brand-text">Pedidos<span>App</span></div>
    </div>

    <nav class="sidebar-menu">
        <!-- ========================================== -->
        <!-- SECCIÓN: NAVEGACIÓN -->
        <!-- ========================================== -->
        <div class="menu-section">
            <div class="menu-section-title">Navegación</div>
            <a href="<?= BASE_URL ?>dashboard" class="menu-item <?= ($_GET['url'] ?? '') === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-th-large menu-icon"></i> Dashboard
            </a>
        </div>

        <!-- ========================================== -->
        <!-- SECCIÓN: GESTIÓN -->
        <!-- ========================================== -->
        <div class="menu-section">
            <div class="menu-section-title">Gestión</div>
            
            <!-- Pedidos (Acordeón) -->
            <div class="menu-item" onclick="toggleSubmenu('submenu-pedidos', this)">
                <i class="fas fa-file-invoice menu-icon"></i> Pedidos
                <i class="fas fa-chevron-right menu-arrow <?= (strpos($_SERVER['REQUEST_URI'], 'pedidos') !== false) ? 'open' : '' ?>"></i>
            </div>
            <div class="submenu <?= (strpos($_SERVER['REQUEST_URI'], 'pedidos') !== false) ? 'open' : '' ?>" id="submenu-pedidos">
                <a href="<?= BASE_URL ?>pedidos" class="menu-item <?= ($_GET['url'] ?? '') === 'pedidos' ? 'active' : '' ?>">
                    <i class="fas fa-list menu-icon"></i> Listar Pedidos
                </a>
                <a href="<?= BASE_URL ?>pedidos/crear" class="menu-item <?= ($_GET['url'] ?? '') === 'pedidos/crear' ? 'active' : '' ?>">
                    <i class="fas fa-plus-circle menu-icon"></i> Nuevo Pedido
                </a>
            </div>

            <!-- Clientes -->
            <a href="<?= BASE_URL ?>clientes" class="menu-item <?= ($_GET['url'] ?? '') === 'clientes' ? 'active' : '' ?>">
                <i class="fas fa-users menu-icon"></i> Clientes
            </a>

            <!-- Productos -->
            <a href="<?= BASE_URL ?>productos" class="menu-item <?= ($_GET['url'] ?? '') === 'productos' ? 'active' : '' ?>">
                <i class="fas fa-boxes menu-icon"></i> Productos
            </a>
        </div>

        <!-- ========================================== -->
        <!-- SECCIÓN: USUARIOS (SIEMPRE VISIBLE PARA ADMIN) -->
        <!-- ========================================== -->
        <?php 
        // Debug: Mostrar rol en comentario HTML
        // Rol actual: <?= $_SESSION['usuario']['rol'] ?? 'No logueado' 
        ?>
        
        <?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['rol'], ['super_admin', 'admin', 'admin_ventas', 'admin_logistica'])): ?>
        <div class="menu-section">
            <div class="menu-section-title">Configuración</div>
            
            <!-- Usuarios (Acordeón) -->
            <div class="menu-item" onclick="toggleSubmenu('submenu-usuarios', this)">
                <i class="fas fa-user-cog menu-icon"></i> Usuarios
                <i class="fas fa-chevron-right menu-arrow <?= (strpos($_SERVER['REQUEST_URI'], 'usuarios') !== false) ? 'open' : '' ?>"></i>
            </div>
            <div class="submenu <?= (strpos($_SERVER['REQUEST_URI'], 'usuarios') !== false) ? 'open' : '' ?>" id="submenu-usuarios">
                <a href="<?= BASE_URL ?>usuarios" class="menu-item <?= ($_GET['url'] ?? '') === 'usuarios' ? 'active' : '' ?>">
                    <i class="fas fa-list menu-icon"></i> Listar Usuarios
                </a>
                <?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['rol'], ['super_admin', 'admin', 'admin_ventas'])): ?>
                <a href="<?= BASE_URL ?>usuarios/crear" class="menu-item <?= ($_GET['url'] ?? '') === 'usuarios/crear' ? 'active' : '' ?>">
                    <i class="fas fa-user-plus menu-icon"></i> Nuevo Usuario
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- ========================================== -->
        <!-- SECCIÓN: LOGÍSTICA -->
        <!-- ========================================== -->
        <?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['rol'], ['super_admin', 'admin', 'admin_logistica', 'asistente_logistica'])): ?>
        <div class="menu-section">
            <div class="menu-section-title">Logística</div>
            <a href="<?= BASE_URL ?>logistica" class="menu-item <?= ($_GET['url'] ?? '') === 'logistica' ? 'active' : '' ?>">
                <i class="fas fa-truck menu-icon"></i> Despachos
            </a>
        </div>
        <?php endif; ?>
    </nav>

    <!-- Footer del Sidebar -->
    <div class="sidebar-footer">
        <div class="user-avatar"><?= strtoupper(substr($_SESSION['usuario']['nombre'] ?? 'U', 0, 2)) ?></div>
        <div>
            <div class="user-name"><?= $_SESSION['usuario']['nombre'] ?? 'Usuario' ?></div>
            <div class="user-role"><?= $_SESSION['usuario']['rol'] ?? '' ?></div>
        </div>
        <a href="<?= BASE_URL ?>logout" class="text-muted" style="margin-left:auto;">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</aside>

<script>
function toggleSubmenu(id, element) {
    var submenu = document.getElementById(id);
    var arrow = element.querySelector('.menu-arrow');
    if (submenu) {
        submenu.classList.toggle('open');
        if (arrow) arrow.classList.toggle('open');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var toggleBtn = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('sidebar');
    
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
    }
});
</script>
