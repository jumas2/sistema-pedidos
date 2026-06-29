<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Estilos centralizados -->
    <link href="<?= BASE_URL ?>public/css/app.css" rel="stylesheet">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-card">
            
            <!-- Header -->
            <div class="login-header">
                <div class="login-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <h1><?= APP_NAME ?></h1>
                <p>Notas de Pedido</p>
            </div>
            
            <!-- Mensajes Flash -->
            <?php $flash = getFlash(); if ($flash): ?>
                <div class="alert-custom alert-<?= $flash['type'] ?>">
                    <i class="fas <?= $flash['type'] === 'danger' ? 'fa-exclamation-circle' : 'fa-info-circle' ?>"></i>
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>
            
            <!-- Formulario -->
            <form method="POST" action="<?= BASE_URL ?>login">
                <div class="form-group">
                    <label>Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" class="form-control" name="email" required placeholder="admin@empresa.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Contraseña</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" class="form-control" name="password" required placeholder="••••••••">
                    </div>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </button>
            </form>
            
            <!-- Footer -->
            <div class="login-footer">
                <span>Usuario: admin@empresa.com</span>
                <span class="separator">•</span>
                <span>Clave: admin123</span>
            </div>
        </div>
    </div>
</body>
</html>
