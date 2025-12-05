<?php
$page_title = 'Iniciar Sesión - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <i class="fas fa-sign-in-alt"></i>
            <h1>Iniciar Sesión</h1>
            <p>Accede a tu cuenta para gestionar tus reservas</p>
        </div>
        
        <form action="login" method="POST" class="auth-form">
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i>
                    Correo Electrónico
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="tu@email.com"
                    required
                    autofocus
                >
            </div>
            
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                    Contraseña
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••"
                    required
                >
            </div>
            
            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember">
                    <span>Recordarme</span>
                </label>
                <a href="<?= url('/forgot-password') ?>" class="link-forgot">¿Olvidaste tu contraseña?</a>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-sign-in-alt"></i>
                Iniciar Sesión
            </button>
        </form>
        
        <div class="auth-footer">
            <p>¿No tienes una cuenta? <a href="<?= url('/register') ?>">Regístrate aquí</a></p>
        </div>
    </div>
</div>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>
