<?php
$page_title = 'Resumen de Reserva - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php';

$flight = session_get('booking_flight');
$category = session_get('booking_category');
$num_passengers = session_get('booking_num_passengers');
$selected_seats = session_get('booking_selected_seats');
$passengers = session_get('booking_passengers');
$total = session_get('booking_total');
?>

<div class="booking-container">
    <div class="booking-steps">
        <div class="step completed"><div class="step-number">✓</div><div class="step-label">Seleccionar</div></div>
        <div class="step completed"><div class="step-number">✓</div><div class="step-label">Asientos</div></div>
        <div class="step completed"><div class="step-number">✓</div><div class="step-label">Pasajeros</div></div>
        <div class="step active"><div class="step-number">4</div><div class="step-label">Confirmar</div></div>
    </div>
    
    <div class="booking-content">
        <h1><i class="fas fa-clipboard-check"></i> Resumen de Reserva</h1>
        
        <div class="summary-section">
            <h2>Información del Vuelo</h2>
            <div class="summary-grid">
                <div><strong>Vuelo:</strong> <?= $flight['numero_vuelo'] ?></div>
                <div><strong>Aerolínea:</strong> <?= escape_html($flight['aerolinea']) ?></div>
                <div><strong>Origen:</strong> <?= escape_html($flight['ciudad_origen']) ?> (<?= $flight['codigo_origen'] ?>)</div>
                <div><strong>Destino:</strong> <?= escape_html($flight['ciudad_destino']) ?> (<?= $flight['codigo_destino'] ?>)</div>
                <div><strong>Salida:</strong> <?= format_datetime($flight['fecha_salida'], 'd/m/Y H:i') ?></div>
                <div><strong>Llegada:</strong> <?= format_datetime($flight['fecha_llegada'], 'd/m/Y H:i') ?></div>
                <div><strong>Clase:</strong> <?= escape_html($category['categoria']) ?></div>
                <div><strong>Precio/pasajero:</strong> <?= format_price($category['precio']) ?></div>
            </div>
        </div>
        
        <div class="summary-section">
            <h2>Pasajeros y Asientos</h2>
            <div class="passengers-list">
                <?php foreach ($passengers as $index => $passenger): ?>
                <div class="passenger-item">
                    <div class="passenger-number"><?= $index + 1 ?></div>
                    <div class="passenger-details">
                        <strong><?= escape_html($passenger['nombre'] . ' ' . $passenger['apellido']) ?></strong>
                        <div><?= $passenger['tipo_documento'] ?>: <?= escape_html($passenger['numero_documento']) ?></div>
                    </div>
                    <div class="passenger-seat">
                        Asiento: <strong><?= $selected_seats[$index] ?? 'N/A' ?></strong>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="summary-section">
            <h2>Resumen de Pago</h2>
            <div class="payment-summary">
                <div class="payment-row">
                    <span>Precio por pasajero:</span>
                    <span><?= format_price($category['precio']) ?></span>
                </div>
                <div class="payment-row">
                    <span>Número de pasajeros:</span>
                    <span><?= $num_passengers ?></span>
                </div>
                <div class="payment-row subtotal">
                    <span>Subtotal:</span>
                    <span><?= format_price($total) ?></span>
                </div>
                <div class="payment-row total">
                    <span>Total a Pagar:</span>
                    <span><?= format_price($total) ?></span>
                </div>
            </div>
        </div>
        
        <div class="important-notice">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Importante:</strong>
                <p>Esta reserva expirará en 24 horas si no se realiza el pago.</p>
                <p>Los asientos seleccionados quedarán reservados temporalmente.</p>
            </div>
        </div>
        
        <form action="<?= url('/booking/confirm') ?>" method="POST">
            <div class="terms-acceptance">
                <label>
                    <input type="checkbox" name="accept_terms" required>
                    Acepto los <a href="#" target="_blank">términos y condiciones</a>
                </label>
            </div>
            
            <div class="form-actions">
                <button type="button" onclick="history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </button>
                <button type="submit" class="btn btn-primary btn-large">
                    <i class="fas fa-check"></i> Confirmar Reserva
                </button>
            </div>
        </form>
    </div>
</div>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>
