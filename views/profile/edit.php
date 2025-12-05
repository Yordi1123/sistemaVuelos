<?php
$page_title = 'Editar Perfil - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php';
?>

<div class="profile-edit-container">
    <h1><i class="fas fa-user-edit"></i> Editar Perfil</h1>
    
    <form action="<?= url('/profile/edit') ?>" method="POST" class="profile-form">
        <h2>Información Personal</h2>
        
        <div class="form-row">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="nombre" value="<?= isset($user['nombre']) ? escape_html($user['nombre']) : '' ?>" required>
            </div>
            
            <div class="form-group">
                <label>Apellido *</label>
                <input type="text" name="apellido" value="<?= isset($user['apellido']) ? escape_html($user['apellido']) : '' ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Teléfono</label>
                <input type="tel" name="telefono" value="<?= isset($user['telefono']) ? escape_html($user['telefono']) : '' ?>">
            </div>
            
            <div class="form-group">
                <label>Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" value="<?= isset($user['fecha_nacimiento']) ? $user['fecha_nacimiento'] : '' ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion" value="<?= isset($user['direccion']) ? escape_html($user['direccion']) : '' ?>">
        </div>
        
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Guardar Cambios
        </button>
    </form>
    
    <form action="<?= url('/profile/change-password') ?>" method="POST" class="profile-form" style="margin-top: 2rem;">
        <h2>Cambiar Contraseña</h2>
        
        <div class="form-group">
            <label>Contraseña Actual *</label>
            <input type="password" name="current_password" required>
        </div>
        
        <div class="form-group">
            <label>Nueva Contraseña *</label>
            <input type="password" name="new_password" required>
        </div>
        
        <div class="form-group">
            <label>Confirmar Nueva Contraseña *</label>
            <input type="password" name="confirm_password" required>
        </div>
        
        <button type="submit" class="btn btn-secondary">
            <i class="fas fa-key"></i> Cambiar Contraseña
        </button>
    </form>
    
    <div class="form-actions">
        <a href="<?= url('/profile/dashboard') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>
