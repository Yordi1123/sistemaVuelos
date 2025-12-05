<?php
$page_title = 'Registrarse - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php';
?>

<div class="auth-container">
    <div class="auth-card auth-card-large">
        <div class="auth-header">
            <i class="fas fa-user-plus"></i>
            <h1>Crear Cuenta</h1>
            <p>Regístrate para comenzar a reservar tus vuelos</p>
        </div>
        
        <form action="register" method="POST" class="auth-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="nombre">
                        <i class="fas fa-user"></i>
                        Nombre *
                    </label>
                    <input 
                        type="text" 
                        id="nombre" 
                        name="nombre" 
                        placeholder="Juan"
                        required
                        autofocus
                    >
                </div>
                
                <div class="form-group">
                    <label for="apellido">
                        <i class="fas fa-user"></i>
                        Apellido *
                    </label>
                    <input 
                        type="text" 
                        id="apellido" 
                        name="apellido" 
                        placeholder="Pérez"
                        required
                    >
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i>
                    Correo Electrónico *
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="tu@email.com"
                    required
                >
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="tipo_documento">
                        <i class="fas fa-id-card"></i>
                        Tipo de Documento
                    </label>
                    <select id="tipo_documento" name="tipo_documento">
                        <option value="DNI">DNI</option>
                        <option value="Pasaporte">Pasaporte</option>
                        <option value="Carnet">Carnet de Extranjería</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="documento_identidad">
                        <i class="fas fa-id-card"></i>
                        Número de Documento
                    </label>
                    <input 
                        type="text" 
                        id="documento_identidad" 
                        name="documento_identidad" 
                        placeholder="12345678"
                    >
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="telefono">
                        <i class="fas fa-phone"></i>
                        Teléfono
                    </label>
                    <input 
                        type="tel" 
                        id="telefono" 
                        name="telefono" 
                        placeholder="+51 999 999 999"
                    >
                </div>
                
                <div class="form-group">
                    <label for="fecha_nacimiento">
                        <i class="fas fa-calendar"></i>
                        Fecha de Nacimiento
                    </label>
                    <input 
                        type="date" 
                        id="fecha_nacimiento" 
                        name="fecha_nacimiento"
                    >
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i>
                        Contraseña *
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Mínimo 8 caracteres"
                        required
                        minlength="8"
                    >
                </div>
                
                <div class="form-group">
                    <label for="password_confirm">
                        <i class="fas fa-lock"></i>
                        Confirmar Contraseña *
                    </label>
                    <input 
                        type="password" 
                        id="password_confirm" 
                        name="password_confirm" 
                        placeholder="Repite tu contraseña"
                        required
                        minlength="8"
                    >
                </div>
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="terms" required>
                    <span>Acepto los <a href="<?= url('/terms') ?>" target="_blank">Términos y Condiciones</a></span>
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-user-plus"></i>
                Crear Cuenta
            </button>
        </form>
        
        <div class="auth-footer">
            <p>¿Ya tienes una cuenta? <a href="<?= url('/login') ?>">Inicia sesión aquí</a></p>
        </div>
    </div>
</div>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>
