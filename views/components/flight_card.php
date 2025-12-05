<?php
/**
 * Componente: Tarjeta de Vuelo
 * Parámetros: $flight (array con datos del vuelo)
 */
?>

<div class="flight-card">
    <div class="flight-card-header">
        <div class="airline-info">
            <span class="airline-name"><?= escape_html($flight['aerolinea']) ?></span>
            <span class="flight-number"><?= $flight['numero_vuelo'] ?></span>
        </div>
        <div class="flight-status status-<?= $flight['estado_vuelo'] ?>">
            <?php
            $status_icons = [
                'programado' => 'clock',
                'a_tiempo' => 'check-circle',
                'retrasado' => 'exclamation-triangle',
                'cancelado' => 'times-circle'
            ];
            $status_labels = [
                'programado' => 'Programado',
                'a_tiempo' => 'A Tiempo',
                'retrasado' => 'Retrasado',
                'cancelado' => 'Cancelado'
            ];
            ?>
            <i class="fas fa-<?= $status_icons[$flight['estado_vuelo']] ?? 'info-circle' ?>"></i>
            <?= $status_labels[$flight['estado_vuelo']] ?? $flight['estado_vuelo'] ?>
            <?php if ($flight['estado_vuelo'] === 'retrasado' && $flight['minutos_retraso'] > 0): ?>
                (+<?= $flight['minutos_retraso'] ?> min)
            <?php endif; ?>
        </div>
    </div>
    
    <div class="flight-card-body">
        <div class="flight-route">
            <div class="route-point">
                <div class="time"><?= format_datetime($flight['fecha_salida'], 'H:i') ?></div>
                <div class="airport-code"><?= $flight['codigo_origen'] ?></div>
                <div class="city"><?= escape_html($flight['ciudad_origen']) ?></div>
            </div>
            
            <div class="route-line">
                <div class="duration">
                    <i class="fas fa-clock"></i>
                    <?= calculate_duration($flight['fecha_salida'], $flight['fecha_llegada']) ?>
                </div>
                <div class="line">
                    <i class="fas fa-plane"></i>
                </div>
                <div class="flight-type">
                    <?= $flight['tipo_vuelo'] === 'directo' ? 'Directo' : 'Con escalas' ?>
                </div>
            </div>
            
            <div class="route-point">
                <div class="time"><?= format_datetime($flight['fecha_llegada'], 'H:i') ?></div>
                <div class="airport-code"><?= $flight['codigo_destino'] ?></div>
                <div class="city"><?= escape_html($flight['ciudad_destino']) ?></div>
            </div>
        </div>
        
        <div class="flight-info">
            <?php if (isset($flight['puerta_embarque'])): ?>
            <div class="info-item">
                <i class="fas fa-door-open"></i>
                <span>Puerta: <?= $flight['puerta_embarque'] ?></span>
            </div>
            <?php endif; ?>
            
            <?php if (isset($flight['terminal'])): ?>
            <div class="info-item">
                <i class="fas fa-building"></i>
                <span>Terminal: <?= $flight['terminal'] ?></span>
            </div>
            <?php endif; ?>
            
            <?php if (isset($flight['total_asientos_disponibles'])): ?>
            <div class="info-item">
                <i class="fas fa-chair"></i>
                <span><?= $flight['total_asientos_disponibles'] ?> asientos disponibles</span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="flight-card-footer">
        <div class="price-info">
            <?php if (isset($flight['precio_minimo'])): ?>
            <div class="price-label">Desde</div>
            <div class="price"><?= format_price($flight['precio_minimo']) ?></div>
            <?php endif; ?>
        </div>
        
        <div class="actions">
            <a href="<?= url('/flights/details?id=' . $flight['id_vuelo']) ?>" class="btn btn-secondary">
                <i class="fas fa-info-circle"></i>
                Ver Detalles
            </a>
            <?php if (is_authenticated()): ?>
            <a href="<?= url('/booking/start?flight=' . $flight['id_vuelo']) ?>" class="btn btn-primary">
                <i class="fas fa-ticket-alt"></i>
                Reservar
            </a>
            <?php else: ?>
            <a href="<?= url('/login') ?>" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i>
                Iniciar Sesión
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
