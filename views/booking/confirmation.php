<?php
// Evitar caché del navegador
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$page_title = 'Reserva Confirmada - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php';
?>

<!-- SIEMPRE MOSTRAR DEBUG -->
<div style="background: #fff3cd; border: 2px solid #856404; padding: 20px; margin: 20px; border-radius: 8px;">
    <h3 style="color: #856404; margin-top: 0;">🔍 DEBUG - Datos del Booking:</h3>
    <pre style="background: white; padding: 15px; border-radius: 4px; overflow-x: auto; max-height: 400px;"><?php print_r($booking); ?></pre>
    <p><strong>codigo_reserva existe:</strong> <?= isset($booking['codigo_reserva']) ? 'SÍ' : 'NO' ?></p>
    <p><strong>Valor de codigo_reserva:</strong> <?= isset($booking['codigo_reserva']) ? $booking['codigo_reserva'] : 'NO EXISTE' ?></p>
    <p><strong>id_reserva:</strong> <?= $booking['id_reserva'] ?? 'NO EXISTE' ?></p>
    <p><strong>URL que se generará:</strong> <?= url('/payment/checkout?booking=' . (isset($booking['codigo_reserva']) ? $booking['codigo_reserva'] : $booking['id_reserva'])) ?></p>
</div>

<div class="booking-container">
    <div class="confirmation-success">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1>¡Reserva Confirmada!</h1>
        <p class="confirmation-message">Su reserva ha sido creada exitosamente</p>
        
        <div class="reservation-code">
            <div class="code-label">Código de Reserva</div>
            <div class="code-value"><?= isset($booking['codigo_reserva']) ? $booking['codigo_reserva'] : 'ERROR: ' . $booking['id_reserva'] ?></div>
        </div>
        
        <div class="confirmation-details">
            <div class="detail-item">
                <i class="fas fa-calendar"></i>
                <div>
                    <strong>Fecha de Reserva</strong>
                    <p><?= format_datetime($booking['fecha_reserva'], 'd/m/Y H:i') ?></p>
                </div>
            </div>
            
            <div class="detail-item">
                <i class="fas fa-clock"></i>
                <div>
                    <strong>Válida hasta</strong>
                    <p><?= format_datetime($booking['fecha_expiracion'], 'd/m/Y H:i') ?></p>
                </div>
            </div>
            
            <div class="detail-item">
                <i class="fas fa-users"></i>
                <div>
                    <strong>Pasajeros</strong>
                    <p><?= $booking['total_pasajeros'] ?> pasajero(s)</p>
                </div>
            </div>
            
            <div class="detail-item">
                <i class="fas fa-money-bill"></i>
                <div>
                    <strong>Total</strong>
                    <p><?= format_price($booking['monto_total']) ?></p>
                </div>
            </div>
        </div>
        
        <div class="next-steps">
            <h2>Próximos Pasos</h2>
            <ol>
                <li>Guarde su código de reserva: <strong><?= $booking['codigo_reserva'] ?></strong></li>
                <li>Complete el pago antes de <?= format_datetime($booking['fecha_expiracion'], 'd/m/Y H:i') ?></li>
                <li>Recibirá su boleto electrónico por email una vez confirmado el pago</li>
            </ol>
        </div>
        
        <div class="confirmation-actions">
            <a href="<?= url('/booking/view?id=' . $booking['id_reserva']) ?>" class="btn btn-secondary">
                <i class="fas fa-eye"></i>
                Ver Detalles de Reserva
            </a>
            <a href="<?= url('/payment/checkout?booking=' . $booking['codigo_reserva']) ?>" class="btn btn-primary btn-large">
                <i class="fas fa-credit-card"></i>
                Proceder al Pago
            </a>
        </div>
        
        <div class="additional-actions">
            <a href="<?= url('/') ?>">Volver al Inicio</a>
            <span>|</span>
            <a href="<?= url('/flights/search') ?>">Buscar Más Vuelos</a>
        </div>
    </div>
</div>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>
