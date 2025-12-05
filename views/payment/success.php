<?php
$page_title = 'Pago Exitoso - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php';
?>

<div class="success-container">
    <div class="success-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    
    <h1>¡Pago Procesado Exitosamente!</h1>
    <p>Su reserva ha sido confirmada y sus boletos han sido generados</p>
    
    <div class="transaction-info">
        <div class="info-item">
            <strong>Código de Transacción:</strong>
            <span><?= escape_html($transaction_code) ?></span>
        </div>
    </div>
    
    <div class="success-actions">
        <a href="<?= url('/profile/dashboard') ?>" class="btn btn-primary btn-large">
            <i class="fas fa-home"></i>
            Ver Mis Reservas
        </a>
        <a href="<?= url('/flights/search') ?>" class="btn btn-secondary">
            <i class="fas fa-search"></i>
            Buscar Más Vuelos
        </a>
    </div>
</div>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>
