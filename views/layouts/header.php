<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Sistema de Pedidos' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- jQuery PRIMERO -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/pedidos.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/pedidos-custom.css">
    
    <style>
        /* ========================================== */
        /* ROOT VARIABLES - TEMA MODERNO */
        /* ========================================== */
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed: 72px;
            --primary: #4f46e5;
            --primary-light: #818cf8;
            --primary-dark: #3730a3;
            --secondary: #0ea5e9;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --sidebar-bg: #0f172a;
            --sidebar-bg-hover: #1e293b;
            --sidebar-text: #cbd5e1;
            --sidebar-text-hover: #ffffff;
            --sidebar-active: #60a5fa;
            --sidebar-border: rgba(255,255,255,0.06);
            --card-radius: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            color: #0f172a;
        }
        
        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* ========================================== */
        /* SIDEBAR MODERNO */
        /* ========================================== */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--sidebar-border);
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
        }
        
        /* Scrollbar elegante */
        .sidebar::-webkit-scrollbar {
            width: 3px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
        
        /* ========================================== */
        /* BRAND / LOGO */
        /* ========================================== */
        .sidebar-brand {
            padding: 20px 20px 16px 20px;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            min-height: 76px;
        }
        .sidebar-brand .brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fff;
            flex-shrink: 0;
            position: relative;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.35);
            transition: var(--transition);
        }
        .sidebar-brand .brand-icon:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 25px rgba(79, 70, 229, 0.5);
        }
        .sidebar-brand .brand-icon::after {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 17px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            opacity: 0.2;
            filter: blur(8px);
            z-index: -1;
        }
        .sidebar-brand .brand-text {
            transition: var(--transition);
            overflow: hidden;
        }
        .sidebar-brand .brand-text h3 {
            color: #ffffff;
            font-size: 18px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.3px;
            background: linear-gradient(135deg, #ffffff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .sidebar-brand .brand-text small {
            color: #64748b;
            font-size: 10px;
            display: block;
            margin-top: 1px;
            font-weight: 500;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }
        
        /* ========================================== */
        /* MENÚ PRINCIPAL */
        /* ========================================== */
        .sidebar-menu {
            list-style: none;
            padding: 16px 12px;
            margin: 0;
            flex: 1;
        }
        
        /* Label de sección */
        .sidebar-menu .menu-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #475569;
            padding: 12px 12px 6px 12px;
            font-weight: 700;
            letter-spacing: 0.8px;
            transition: var(--transition);
            opacity: 0.7;
        }
        
        /* Items del menú */
        .sidebar-menu .menu-item {
            border-radius: 12px;
            margin-bottom: 2px;
            transition: var(--transition);
            position: relative;
        }
        .sidebar-menu .menu-item:hover {
            background: rgba(255,255,255,0.04);
        }
        
        .sidebar-menu .menu-item > a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 10px 14px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 12px;
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        /* Efecto de brillo al hover */
        .sidebar-menu .menu-item > a::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }
        .sidebar-menu .menu-item > a:hover::before {
            transform: translateX(100%);
        }
        
        .sidebar-menu .menu-item > a:hover {
            color: var(--sidebar-text-hover);
            background: rgba(255,255,255,0.06);
            transform: translateX(4px);
        }
        .sidebar-menu .menu-item > a.active {
            color: var(--sidebar-active);
            background: rgba(79, 70, 229, 0.12);
            box-shadow: inset 3px 0 0 var(--primary);
        }
        .sidebar-menu .menu-item > a.active i {
            color: var(--sidebar-active);
        }
        
        .sidebar-menu .menu-item > a i {
            width: 20px;
            text-align: center;
            font-size: 16px;
            color: #64748b;
            transition: var(--transition);
            flex-shrink: 0;
        }
        .sidebar-menu .menu-item > a:hover i {
            color: #94a3b8;
            transform: scale(1.1);
        }
        .sidebar-menu .menu-item > a.active i {
            color: var(--sidebar-active);
        }
        
        .sidebar-menu .menu-item > a span {
            transition: var(--transition);
            white-space: nowrap;
        }
        
        /* Badges animados */
        .menu-badge {
            margin-left: auto;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 10px;
            border-radius: 20px;
            min-width: 20px;
            text-align: center;
            transition: var(--transition);
            animation: pulse-badge 2s ease-in-out infinite;
        }
        .menu-badge.warning {
            background: linear-gradient(135deg, var(--warning), #d97706);
        }
        .menu-badge.success {
            background: linear-gradient(135deg, var(--success), #059669);
        }
        .menu-badge.danger {
            background: linear-gradient(135deg, var(--danger), #dc2626);
        }
        
        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        /* Flecha del acordeón */
        .menu-arrow {
            margin-left: auto;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 12px;
            color: #475569;
            flex-shrink: 0;
        }
        .menu-item.open .menu-arrow {
            transform: rotate(180deg);
            color: var(--sidebar-active);
        }
        .menu-item > a:hover .menu-arrow {
            color: #94a3b8;
        }
        
        /* Tooltip visual para items sin texto */
        .menu-item > a .tooltip-hint {
            display: none;
        }
        
        /* ========================================== */
        /* SUBMENÚ (ACORDEÓN) */
        /* ========================================== */
        .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.3s ease;
            opacity: 0;
        }
        .menu-item.open .submenu {
            max-height: 600px;
            opacity: 1;
        }
        
        .submenu li {
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.3s ease;
            transition-delay: 0.05s;
        }
        .menu-item.open .submenu li {
            opacity: 1;
            transform: translateX(0);
        }
        .menu-item.open .submenu li:nth-child(2) { transition-delay: 0.1s; }
        .menu-item.open .submenu li:nth-child(3) { transition-delay: 0.15s; }
        .menu-item.open .submenu li:nth-child(4) { transition-delay: 0.2s; }
        
        .submenu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 14px 8px 48px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 13px;
            font-weight: 400;
            border-radius: 10px;
            transition: var(--transition);
            position: relative;
        }
        .submenu li a::before {
            content: '';
            position: absolute;
            left: 32px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 4px;
            background: #475569;
            border-radius: 50%;
            transition: var(--transition);
        }
        .submenu li a:hover {
            color: var(--sidebar-text-hover);
            background: rgba(255,255,255,0.05);
            transform: translateX(4px);
        }
        .submenu li a:hover::before {
            background: var(--primary-light);
            box-shadow: 0 0 10px rgba(79, 70, 229, 0.3);
        }
        .submenu li a.active {
            color: var(--sidebar-active);
            background: rgba(79, 70, 229, 0.08);
        }
        .submenu li a.active::before {
            background: var(--primary);
            width: 6px;
            height: 6px;
            box-shadow: 0 0 15px rgba(79, 70, 229, 0.4);
        }
        .submenu li a i {
            width: 16px;
            text-align: center;
            font-size: 12px;
            color: #475569;
            flex-shrink: 0;
        }
        .submenu li a:hover i {
            color: #94a3b8;
        }
        .submenu li a.active i {
            color: var(--sidebar-active);
        }
        
        /* ========================================== */
        /* FOOTER DEL SIDEBAR */
        /* ========================================== */
        .sidebar-footer {
            padding: 14px 16px;
            border-top: 1px solid var(--sidebar-border);
            margin-top: auto;
        }
        .sidebar-footer .user-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 14px;
            background: rgba(255,255,255,0.03);
            transition: var(--transition);
            cursor: pointer;
        }
        .sidebar-footer .user-card:hover {
            background: rgba(255,255,255,0.06);
        }
        .sidebar-footer .user-avatar {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            color: #fff;
            flex-shrink: 0;
            transition: var(--transition);
            position: relative;
        }
        .sidebar-footer .user-avatar::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 10px;
            height: 10px;
            background: var(--success);
            border-radius: 50%;
            border: 2px solid var(--sidebar-bg);
        }
        .sidebar-footer .user-card:hover .user-avatar {
            transform: scale(1.05);
        }
        .sidebar-footer .user-details {
            flex: 1;
            min-width: 0;
        }
        .sidebar-footer .user-details .name {
            color: #e2e8f0;
            font-size: 13px;
            font-weight: 600;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-footer .user-details .role {
            color: #94a3b8;
            font-size: 11px;
            display: block;
            font-weight: 400;
        }
        .sidebar-footer .logout-btn {
            color: #64748b;
            text-decoration: none;
            font-size: 16px;
            padding: 8px 10px;
            border-radius: 10px;
            transition: var(--transition);
            flex-shrink: 0;
        }
        .sidebar-footer .logout-btn:hover {
            color: var(--danger);
            background: rgba(239, 68, 68, 0.1);
            transform: rotate(-5deg);
        }
        
        /* ========================================== */
        /* TOGGLE COLLAPSE (oculto por defecto) */
        /* ========================================== */
        .sidebar-toggle {
            display: none;
        }
        
        /* ========================================== */
        /* CONTENIDO PRINCIPAL */
        /* ========================================== */
        .main-content {
            flex: 1;
            padding: 24px 32px 32px 32px;
            min-width: 0;
            background: #f1f5f9;
            transition: var(--transition);
        }
        
        /* ========================================== */
        /* RESPONSIVE */
        /* ========================================== */
        @media (max-width: 992px) {
            .sidebar {
                width: var(--sidebar-collapsed);
            }
            .sidebar-brand .brand-text,
            .sidebar-menu .menu-label,
            .sidebar-menu .menu-item > a span,
            .sidebar-menu .menu-item > a .menu-arrow,
            .sidebar-menu .menu-item > a .menu-badge,
            .submenu,
            .sidebar-footer .user-details,
            .sidebar-footer .logout-btn span {
                display: none;
            }
            .sidebar-brand {
                padding: 16px;
                justify-content: center;
            }
            .sidebar-brand .brand-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
            .sidebar-menu {
                padding: 8px 6px;
            }
            .sidebar-menu .menu-item > a {
                padding: 12px;
                justify-content: center;
                border-radius: 10px;
            }
            .sidebar-menu .menu-item > a i {
                font-size: 18px;
                width: auto;
            }
            .sidebar-menu .menu-item > a:hover {
                transform: none;
            }
            .sidebar-menu .menu-item > a.active {
                box-shadow: none;
                background: rgba(79, 70, 229, 0.15);
            }
            .sidebar-footer .user-card {
                justify-content: center;
                padding: 8px;
            }
            .sidebar-footer .logout-btn {
                padding: 8px;
            }
            .main-content {
                padding: 16px 20px;
            }
        }
        
        @media (max-width: 576px) {
            .main-content {
                padding: 12px 16px;
            }
        }
        
        /* ========================================== */
        /* UTILIDADES GENERALES */
        /* ========================================== */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .page-title {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -0.3px;
        }
        .title-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 14px;
            font-size: 17px;
        }
        .title-icon.primary {
            background: #eef2ff;
            color: var(--primary);
        }
        .title-icon.success {
            background: #d1fae5;
            color: var(--success);
        }
        .title-icon.warning {
            background: #fef3c7;
            color: var(--warning);
        }
        .title-icon.danger {
            background: #fee2e2;
            color: var(--danger);
        }
        .page-subtitle {
            color: #64748b;
            margin: 2px 0 0 54px;
            font-size: 14px;
            font-weight: 400;
        }
        
        /* Botones modernos */
        .btn-primary-modern {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }
        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(79, 70, 229, 0.4);
            color: #fff;
            text-decoration: none;
        }
        .btn-secondary-modern {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #fff;
            color: #0f172a;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: var(--transition);
        }
        .btn-secondary-modern:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
            text-decoration: none;
            transform: translateY(-1px);
        }
        
        /* Cards */
        .card {
            background: #fff;
            border: none;
            border-radius: var(--card-radius);
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            margin-bottom: 20px;
            transition: var(--transition);
        }
        .card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        .card-header {
            border-radius: var(--card-radius) var(--card-radius) 0 0 !important;
            font-weight: 600;
            padding: 14px 20px;
        }
        .card-body { padding: 20px; }
        
        /* Formularios */
        .form-group { margin-bottom: 16px; }
        .form-group label {
            font-weight: 600;
            font-size: 13px;
            color: #0f172a;
            margin-bottom: 4px;
            display: block;
        }
        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 8px 14px;
            font-size: 14px;
            width: 100%;
            transition: var(--transition);
            background: #fafbfc;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.08);
            outline: none;
            background: #fff;
        }
        .input-group-text {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px 0 0 10px;
            font-size: 14px;
        }
        
        /* Tablas */
        .table { font-size: 14px; margin-bottom: 0; }
        .table thead th {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
            color: #64748b;
            padding: 10px 12px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .table tbody td { padding: 10px 12px; vertical-align: middle; }
        .table tbody tr {
            transition: var(--transition);
        }
        .table tbody tr:hover {
            background: #f8fafc;
        }
        
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .mt-3 { margin-top: 16px; }
        .mb-3 { margin-bottom: 16px; }
        .mb-4 { margin-bottom: 24px; }
        
        /* Estados */
        .estado-pendiente {
            background: #fef3c7;
            color: #92400e;
        }
        .estado-atendido {
            background: #d1fae5;
            color: #065f46;
        }
        .estado-anulado {
            background: #fee2e2;
            color: #991b1b;
        }
        .estado-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .alert {
            border-radius: 12px;
            border: none;
            padding: 14px 20px;
        }
        
        /* Animaciones de entrada */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .fade-in-up {
            animation: fadeInUp 0.5s ease forwards;
        }
    </style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="app-wrapper">
    <!-- ========================================== -->
    <!-- SIDEBAR ULTRA MODERNO -->
    <!-- ========================================== -->
    <nav class="sidebar" id="sidebar">
        <!-- Brand -->
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="fas fa-cube"></i>
            </div>
            <div class="brand-text">
                <h3>BIOSERVICE</h3>
                <small>Sistema de Pedidos</small>
            </div>
        </div>
        
        <!-- Menú -->
        <ul class="sidebar-menu">
            <!-- SECCIÓN PRINCIPAL -->
            <li class="menu-label">PRINCIPAL</li>
            
            <li class="menu-item">
                <a href="<?= BASE_URL ?>dashboard" class="<?= ($_GET['controller'] ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="menu-item <?= ($_GET['controller'] ?? '') === 'pedidos' ? 'open' : '' ?>">
                <a href="#" onclick="toggleSubmenu(this)">
                    <i class="fas fa-file-invoice"></i>
                    <span>Pedidos</span>
                    <?php 
                        $pendientes = (new Pedido())->getPendientes();
                        if (count($pendientes) > 0): 
                    ?>
                        <span class="menu-badge danger"><?= count($pendientes) ?></span>
                    <?php endif; ?>
                    <i class="fas fa-chevron-down menu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="<?= BASE_URL ?>pedidos" class="<?= ($_GET['action'] ?? '') === '' ? 'active' : '' ?>">
                            <i class="fas fa-list"></i> Listar Pedidos
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>pedidos/crear" class="<?= ($_GET['action'] ?? '') === 'crear' ? 'active' : '' ?>">
                            <i class="fas fa-plus-circle"></i> Nuevo Pedido
                        </a>
                    </li>
                </ul>
            </li>
            
            <li class="menu-item <?= ($_GET['controller'] ?? '') === 'clientes' ? 'open' : '' ?>">
                <a href="#" onclick="toggleSubmenu(this)">
                    <i class="fas fa-users"></i>
                    <span>Clientes</span>
                    <i class="fas fa-chevron-down menu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="<?= BASE_URL ?>clientes" class="<?= ($_GET['action'] ?? '') === '' ? 'active' : '' ?>">
                            <i class="fas fa-list"></i> Listar Clientes
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>clientes/crear" class="<?= ($_GET['action'] ?? '') === 'crear' ? 'active' : '' ?>">
                            <i class="fas fa-user-plus"></i> Nuevo Cliente
                        </a>
                    </li>
                </ul>
            </li>
            
            <li class="menu-item <?= ($_GET['controller'] ?? '') === 'productos' ? 'open' : '' ?>">
                <a href="#" onclick="toggleSubmenu(this)">
                    <i class="fas fa-boxes"></i>
                    <span>Productos</span>
                    <i class="fas fa-chevron-down menu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="<?= BASE_URL ?>productos" class="<?= ($_GET['action'] ?? '') === '' ? 'active' : '' ?>">
                            <i class="fas fa-list"></i> Listar Productos
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>productos/crear" class="<?= ($_GET['action'] ?? '') === 'crear' ? 'active' : '' ?>">
                            <i class="fas fa-box"></i> Nuevo Producto
                        </a>
                    </li>
                </ul>
                 <!-- LOGÍSTICA -->
            <li class="menu-item <?= ($_GET['controller'] ?? '') === 'logistica' ? 'open active' : '' ?>">
                <a href="#" onclick="toggleSubmenu(this)">
                    <i class="fas fa-truck"></i>
                    <span>Logística</span>
                    <i class="fas fa-chevron-down menu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="<?= BASE_URL ?>logistica" class="<?= ($_GET['action'] ?? '') === '' ? 'active' : '' ?>">
                            <i class="fas fa-list"></i> Despachos
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>logistica/misDespachos" class="<?= ($_GET['action'] ?? '') === 'misDespachos' ? 'active' : '' ?>">
                            <i class="fas fa-user-tie"></i> Mis Despachos
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>logistica/vehiculos" class="<?= ($_GET['action'] ?? '') === 'vehiculos' ? 'active' : '' ?>">
                            <i class="fas fa-car"></i> Vehículos
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>logistica/transportistas" class="<?= ($_GET['action'] ?? '') === 'transportistas' ? 'active' : '' ?>">
                            <i class="fas fa-users"></i> Transportistas
                        </a>
                    </li>
                </ul>
            </li>
            
            </li>
            
                        <!-- SECCIÓN ADMINISTRACIÓN -->
            <?php if (tieneRol('admin') || tieneRol('super_admin')): ?>
            <li class="menu-label" style="margin-top:16px;">ADMINISTRACIÓN</li>
            
            <!-- EMPRESA -->
            <li class="menu-item <?= ($_GET['controller'] ?? '') === 'empresa' ? 'open active' : '' ?>">
                <a href="#" onclick="toggleSubmenu(this)">
                    <i class="fas fa-building"></i>
                    <span>Empresa</span>
                    <i class="fas fa-chevron-down menu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="<?= BASE_URL ?>empresa" class="<?= ($_GET['action'] ?? '') === '' ? 'active' : '' ?>">
                            <i class="fas fa-cog"></i> Configuración
                        </a>
                    </li>
                </ul>
               
            <!-- USUARIOS -->
            <li class="menu-item <?= ($_GET['controller'] ?? '') === 'usuarios' ? 'open' : '' ?>">
                <a href="#" onclick="toggleSubmenu(this)">
                    <i class="fas fa-user-cog"></i>
                    <span>Usuarios</span>
                    <i class="fas fa-chevron-down menu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="<?= BASE_URL ?>usuarios" class="<?= ($_GET['action'] ?? '') === '' ? 'active' : '' ?>">
                            <i class="fas fa-list"></i> Listar Usuarios
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>usuarios/crear" class="<?= ($_GET['action'] ?? '') === 'crear' ? 'active' : '' ?>">
                            <i class="fas fa-user-plus"></i> Nuevo Usuario
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>
        </ul>
        
        <!-- Footer con información del usuario -->
        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">
                    <?= strtoupper(substr($_SESSION['usuario']['nombre'] ?? 'U', 0, 1)) ?>
                </div>
                <div class="user-details">
                    <span class="name"><?= $_SESSION['usuario']['nombre'] ?? 'Usuario' ?></span>
                    <span class="role"><?= $_SESSION['usuario']['rol'] ?? 'Sin rol' ?></span>
                </div>
                <a href="<?= BASE_URL ?>logout" class="logout-btn" title="Cerrar Sesión">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- ========================================== -->
    <!-- CONTENIDO PRINCIPAL -->
    <!-- ========================================== -->
    <main class="main-content">
        <?php if ($flash = getFlash()): ?>
            <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show fade-in-up" role="alert">
                <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : ($flash['type'] === 'danger' ? 'exclamation-circle' : 'info-circle') ?> me-2"></i>
                <?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
