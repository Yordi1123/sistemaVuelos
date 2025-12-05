<?php
$page_title = 'Datos de Pasajeros - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php';

$num_passengers = session_get('booking_num_passengers');
$selected_seats = session_get('booking_selected_seats');
$user = session_get('user');
?>

<div class="booking-container">
    <div class="booking-steps">
        <div class="step completed"><div class="step-number">✓</div><div class="step-label">Seleccionar</div></div>
        <div class="step completed"><div class="step-number">✓</div><div class="step-label">Asientos</div></div>
        <div class="step active"><div class="step-number">3</div><div class="step-label">Pasajeros</div></div>
        <div class="step"><div class="step-number">4</div><div class="step-label">Confirmar</div></div>
    </div>
    
    <div class="booking-content">
        <h1><i class="fas fa-users"></i> Información de Pasajeros</h1>
        
        <form action="<?= url('/booking/summary') ?>" method="POST" class="booking-form">
            <?php for ($i = 0; $i < $num_passengers; $i++): ?>
            <div class="passenger-section">
                <h3>Pasajero <?= $i + 1 ?> - Asiento <?= isset($selected_seats[$i]) ? $selected_seats[$i] : 'N/A' ?></h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" 
                               name="passengers[<?= $i ?>][nombre]" 
                               value="<?= $i === 0 && isset($user['nombre']) ? escape_html($user['nombre']) : '' ?>"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label>Apellido *</label>
                        <input type="text" 
                               name="passengers[<?= $i ?>][apellido]" 
                               value="<?= $i === 0 && isset($user['apellido']) ? escape_html($user['apellido']) : '' ?>"
                               required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Tipo de Documento *</label>
                        <select name="passengers[<?= $i ?>][tipo_documento]" required>
                            <option value="DNI" <?= $i === 0 && isset($user['tipo_documento']) && $user['tipo_documento'] === 'DNI' ? 'selected' : '' ?>>DNI</option>
                            <option value="Pasaporte" <?= $i === 0 && isset($user['tipo_documento']) && $user['tipo_documento'] === 'Pasaporte' ? 'selected' : '' ?>>Pasaporte</option>
                            <option value="CE">Carnet de Extranjería</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Número de Documento *</label>
                        <input type="text" 
                               name="passengers[<?= $i ?>][numero_documento]" 
                               value="<?= $i === 0 && isset($user['numero_documento']) ? escape_html($user['numero_documento']) : '' ?>"
                               required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" 
                               name="passengers[<?= $i ?>][fecha_nacimiento]"
                               value="<?= $i === 0 && isset($user['fecha_nacimiento']) ? $user['fecha_nacimiento'] : '' ?>"
                               max="<?= date('Y-m-d') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Género</label>
                        <select name="passengers[<?= $i ?>][genero]">
                            <option value="">Seleccionar...</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="O">Otro</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" 
                               name="passengers[<?= $i ?>][email]"
                               value="<?= $i === 0 && isset($user['email']) ? escape_html($user['email']) : '' ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="tel" 
                               name="passengers[<?= $i ?>][telefono]"
                               value="<?= $i === 0 && isset($user['telefono']) ? escape_html($user['telefono']) : '' ?>">
                    </div>
                </div>
                
                <input type="hidden" name="passengers[<?= $i ?>][tipo_pasajero]" value="adulto">
                <input type="hidden" name="passengers[<?= $i ?>][nacionalidad]" value="Peruana">
            </div>
            <?php endfor; ?>
            
            <div class="form-actions">
                <button type="button" onclick="history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </button>
                <button type="submit" class="btn btn-primary btn-large">
                    <i class="fas fa-arrow-right"></i> Continuar a Resumen
                </button>
            </div>
        </form>
    </div>
</div>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>
