<?php
$page_title = 'Detalles del Vuelo ' . $flight['numero_vuelo'] . ' - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php';
?>

<div class="flight-details-container">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="<?= url('/') ?>">Inicio</a>
        <i class="fas fa-chevron-right"></i>
        <a href="<?= url('/flights/search') ?>">Buscar Vuelos</a>
        <i class="fas fa-chevron-right"></i>
        <span>Vuelo <?= $flight['numero_vuelo'] ?></span>
    </div>
    
    <!-- Header del vuelo -->
    <div class="flight-details-header">
        <div class="flight-title">
            <h1>
                <i class="fas fa-plane"></i>
                Vuelo <?= $flight['numero_vuelo'] ?>
            </h1>
            <div class="airline-badge">
                <?= escape_html($flight['aerolinea']) ?> (<?= $flight['codigo_aerolinea'] ?>)
            </div>
        </div>
        
        <div class="flight-status-badge status-<?= $flight['estado_vuelo'] ?>">
            <i class="fas fa-info-circle"></i>
            <?php
            $status_labels = [
                'programado' => 'Programado',
                'a_tiempo' => 'A Tiempo',
                'retrasado' => 'Retrasado',
                'cancelado' => 'Cancelado'
            ];
            echo $status_labels[$flight['estado_vuelo']] ?? $flight['estado_vuelo'];
            ?>
        </div>
    </div>
    
    <!-- Información de ruta -->
    <div class="route-details">
        <div class="route-point-detail">
            <div class="airport-info">
                <div class="airport-code"><?= $flight['codigo_origen'] ?></div>
                <div class="airport-name"><?= escape_html($flight['aeropuerto_origen']) ?></div>
                <div class="city-country">
                    <?= escape_html($flight['ciudad_origen']) ?>, <?= escape_html($flight['pais_origen']) ?>
                </div>
            </div>
            <div class="time-info">
                <div class="time-label">Salida</div>
                <div class="time-value"><?= format_datetime($flight['fecha_salida'], 'H:i') ?></div>
                <div class="date-value"><?= format_date($flight['fecha_salida'], 'd/m/Y') ?></div>
            </div>
        </div>
        
        <div class="route-connection">
            <div class="connection-line"></div>
            <div class="connection-info">
                <div class="duration">
                    <i class="fas fa-clock"></i>
                    <?= calculate_duration($flight['fecha_salida'], $flight['fecha_llegada']) ?>
                </div>
                <div class="flight-type">
                    <?= $flight['tipo_vuelo'] === 'directo' ? 'Vuelo Directo' : 'Con Escalas' ?>
                </div>
            </div>
        </div>
        
        <div class="route-point-detail">
            <div class="airport-info">
                <div class="airport-code"><?= $flight['codigo_destino'] ?></div>
                <div class="airport-name"><?= escape_html($flight['aeropuerto_destino']) ?></div>
                <div class="city-country">
                    <?= escape_html($flight['ciudad_destino']) ?>, <?= escape_html($flight['pais_destino']) ?>
                </div>
            </div>
            <div class="time-info">
                <div class="time-label">Llegada</div>
                <div class="time-value"><?= format_datetime($flight['fecha_llegada'], 'H:i') ?></div>
                <div class="date-value"><?= format_date($flight['fecha_llegada'], 'd/m/Y') ?></div>
            </div>
        </div>
    </div>
    
    <!-- Información adicional del vuelo -->
    <div class="flight-additional-info">
        <div class="info-grid">
            <div class="info-item">
                <i class="fas fa-door-open"></i>
                <div class="info-label">Puerta de Embarque</div>
                <div class="info-value"><?= $flight['puerta_embarque'] ?? 'Por confirmar' ?></div>
            </div>
            <div class="info-item">
                <i class="fas fa-building"></i>
                <div class="info-label">Terminal</div>
                <div class="info-value"><?= $flight['terminal'] ?? 'Por confirmar' ?></div>
            </div>
            <div class="info-item">
                <i class="fas fa-users"></i>
                <div class="info-label">Capacidad Total</div>
                <div class="info-value"><?= $flight['capacidad_total'] ?> pasajeros</div>
            </div>
            <?php if ($flight['estado_vuelo'] === 'retrasado' && $flight['minutos_retraso'] > 0): ?>
            <div class="info-item">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="info-label">Retraso</div>
                <div class="info-value"><?= $flight['minutos_retraso'] ?> minutos</div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Tarifas disponibles -->
    <div class="fares-section">
        <h2><i class="fas fa-tag"></i> Tarifas Disponibles</h2>
        
        <div class="fares-grid">
            <?php foreach ($fares as $fare): ?>
            <div class="fare-card">
                <div class="fare-header">
                    <h3><?= escape_html($fare['categoria']) ?></h3>
                    <div class="fare-price"><?= format_price($fare['precio']) ?></div>
                </div>
                
                <div class="fare-body">
                    <div class="fare-description">
                        <?= escape_html($fare['descripcion']) ?>
                    </div>
                    
                    <div class="fare-services">
                        <h4>Servicios Incluidos:</h4>
                        <div class="services-list">
                            <?php
                            $services = explode(',', $fare['servicios_incluidos']);
                            foreach ($services as $service):
                            ?>
                            <div class="service-item">
                                <i class="fas fa-check"></i>
                                <?= escape_html(trim($service)) ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="fare-availability">
                        <i class="fas fa-chair"></i>
                        <strong><?= $fare['asientos_disponibles'] ?></strong> de <?= $fare['asientos_totales'] ?> asientos disponibles
                    </div>
                    
                    <div class="fare-policies">
                        <div class="policy-item">
                            <i class="fas fa-<?= $fare['permite_cambios'] ? 'check' : 'times' ?>"></i>
                            <?= $fare['permite_cambios'] ? 'Permite cambios' : 'No permite cambios' ?>
                        </div>
                        <div class="policy-item">
                            <i class="fas fa-<?= $fare['permite_reembolso'] ? 'check' : 'times' ?>"></i>
                            <?= $fare['permite_reembolso'] ? 'Reembolsable' : 'No reembolsable' ?>
                        </div>
                    </div>
                </div>
                
                <div class="fare-footer">
                    <?php if ($fare['asientos_disponibles'] > 0): ?>
                        <?php if (is_authenticated()): ?>
                        <a href="<?= url('/booking/start?flight=' . $flight['id_vuelo'] . '&category=' . $fare['id_categoria']) ?>" 
                           class="btn btn-primary btn-block">
                            <i class="fas fa-ticket-alt"></i>
                            Reservar en <?= escape_html($fare['categoria']) ?>
                        </a>
                        <?php else: ?>
                        <a href="<?= url('/login') ?>" class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt"></i>
                            Iniciar Sesión para Reservar
                        </a>
                        <?php endif; ?>
                    <?php else: ?>
                    <button class="btn btn-secondary btn-block" disabled>
                        <i class="fas fa-times-circle"></i>
                        No Disponible
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Botones de acción -->
    <div class="details-actions">
        <a href="<?= url('/flights/search') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Volver a Búsqueda
        </a>
    </div>
</div>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>
