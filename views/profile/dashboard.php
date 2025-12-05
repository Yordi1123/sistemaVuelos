<?php
$page_title = 'Mi Perfil - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php';
?>

<div class="dashboard-container">
    <h1><i class="fas fa-user"></i> Mi Perfil</h1>
    
    <div class="dashboard-grid">
        <div class="profile-card">
            <h2>Información Personal</h2>
            <div class="profile-info">
                <p><strong>Nombre:</strong> <?= escape_html($user['nombre'] . ' ' . $user['apellido']) ?></p>
                <p><strong>Email:</strong> <?= escape_html($user['email']) ?></p>
                <p><strong>Teléfono:</strong> <?= escape_html($user['telefono'] ?? 'No especificado') ?></p>
                <p><strong>Documento:</strong> <?= $user['tipo_documento'] ?> - <?= escape_html($user['numero_documento']) ?></p>
            </div>
            <a href="<?= url('/profile/edit') ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar Perfil
            </a>
        </div>
        
        <div class="bookings-section">
            <h2>Mis Reservas</h2>
            
            <?php if (empty($bookings)): ?>
                <p>No tienes reservas aún.</p>
                <a href="<?= url('/flights/search') ?>" class="btn btn-primary">Buscar Vuelos</a>
            <?php else: ?>
                <div class="bookings-list">
                    <?php foreach ($bookings as $booking): ?>
                    <div class="booking-item">
                        <div class="booking-header">
                            <strong><?= $booking['codigo_reserva'] ?></strong>
                            <span class="badge badge-<?= $booking['estado_reserva'] === 'confirmada' ? 'success' : 'warning' ?>">
                                <?= ucfirst($booking['estado_reserva']) ?>
                            </span>
                        </div>
                        <div class="booking-details">
                            <p><i class="fas fa-calendar"></i> <?= format_date($booking['fecha_reserva'], 'd/m/Y') ?></p>
                            <p><i class="fas fa-users"></i> <?= $booking['total_pasajeros'] ?> pasajero(s)</p>
                            <p><i class="fas fa-money-bill"></i> <?= format_price($booking['monto_total']) ?></p>
                        </div>
                        <?php if ($booking['estado_reserva'] === 'pendiente'): ?>
                        <a href="<?= url('/payment/checkout?booking=' . $booking['codigo_reserva']) ?>" class="btn btn-sm btn-primary">
                            Pagar Ahora
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>
