<?php
$page_title = 'Pago - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php';
?>

<div class="payment-container">
    <h1><i class="fas fa-credit-card"></i> Procesar Pago</h1>
    
    <div class="payment-grid">
        <div class="payment-form-section">
            <h2>Información de Pago</h2>
            
            <form action="<?= url('/payment/process') ?>" method="POST" id="paymentForm">
                <input type="hidden" name="booking_id" value="<?= $booking['id_reserva'] ?>">
                <input type="hidden" name="amount" value="<?= $booking['monto_total'] ?>">
                
                <div class="form-group">
                    <label>Número de Tarjeta *</label>
                    <input type="text" name="card_number" maxlength="16" placeholder="1234 5678 9012 3456" required>
                </div>
                
                <div class="form-group">
                    <label>Nombre del Titular *</label>
                    <input type="text" name="card_name" placeholder="Como aparece en la tarjeta" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha de Expiración *</label>
                        <input type="text" name="card_expiry" placeholder="MM/AA" maxlength="5" required>
                    </div>
                    
                    <div class="form-group">
                        <label>CVV *</label>
                        <input type="text" name="card_cvv" maxlength="4" placeholder="123" required>
                    </div>
                </div>
                
                <div class="payment-note">
                    <i class="fas fa-lock"></i>
                    <p>Este es un sistema de demostración. No se procesarán pagos reales.</p>
                </div>
                
                <button type="submit" class="btn btn-primary btn-large btn-block">
                    <i class="fas fa-check"></i>
                    Pagar <?= format_price($booking['monto_total']) ?>
                </button>
            </form>
        </div>
        
        <div class="payment-summary-section">
            <h2>Resumen de Reserva</h2>
            
            <div class="summary-card">
                <div class="summary-item">
                    <strong>Código de Reserva:</strong>
                    <span><?= $booking['codigo_reserva'] ?></span>
                </div>
                
                <div class="summary-item">
                    <strong>Pasajeros:</strong>
                    <span><?= $booking['total_pasajeros'] ?></span>
                </div>
                
                <div class="summary-item">
                    <strong>Estado:</strong>
                    <span class="badge badge-warning">Pendiente de Pago</span>
                </div>
                
                <div class="summary-item">
                    <strong>Expira:</strong>
                    <span><?= format_datetime($booking['fecha_expiracion'], 'd/m/Y H:i') ?></span>
                </div>
                
                <div class="summary-total">
                    <strong>Total a Pagar:</strong>
                    <span class="total-amount"><?= format_price($booking['monto_total']) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>
