<?php
$page_title = 'Selección de Asientos - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php';

$flight = session_get('booking_flight');
$category = session_get('booking_category');
$num_passengers = session_get('booking_num_passengers');
$seatMap = session_get('booking_seat_map');
?>

<div class="booking-container">
    <div class="booking-steps">
        <div class="step completed"><div class="step-number">✓</div><div class="step-label">Seleccionar</div></div>
        <div class="step active"><div class="step-number">2</div><div class="step-label">Asientos</div></div>
        <div class="step"><div class="step-number">3</div><div class="step-label">Pasajeros</div></div>
        <div class="step"><div class="step-number">4</div><div class="step-label">Confirmar</div></div>
    </div>
    
    <div class="booking-content">
        <h1><i class="fas fa-chair"></i> Seleccionar Asientos</h1>
        
        <div class="seat-selection-info">
            <p>Seleccione <strong><?= $num_passengers ?></strong> asiento(s) en clase <strong><?= escape_html($category['categoria']) ?></strong></p>
            <div class="seat-legend">
                <div class="legend-item"><span class="seat available"></span> Disponible</div>
                <div class="legend-item"><span class="seat occupied"></span> Ocupado</div>
                <div class="legend-item"><span class="seat selected"></span> Seleccionado</div>
            </div>
        </div>
        
        <form action="<?= url('/booking/passenger-info') ?>" method="POST" id="seatSelectionForm">
            <input type="hidden" name="selected_seats" id="selectedSeatsInput">
            
            <div class="seat-map-container">
                <div class="seat-map">
                    <div class="plane-nose">✈</div>
                    
                    <?php foreach ($seatMap as $row => $seats): ?>
                    <div class="seat-row">
                        <div class="row-number"><?= $row ?></div>
                        <div class="seats">
                            <?php 
                            $columns = ['A', 'B', 'C', 'D', 'E', 'F'];
                            foreach ($columns as $col): 
                                if (isset($seats[$col])):
                                    $seat = $seats[$col];
                                    $seatClass = 'seat';
                                    $seatClass .= ($seat['estado'] === 'disponible') ? ' available' : ' occupied';
                                    $disabled = ($seat['estado'] !== 'disponible') ? 'disabled' : '';
                            ?>
                            <button type="button" 
                                    class="<?= $seatClass ?>" 
                                    data-seat-id="<?= $seat['id_asiento'] ?>"
                                    data-seat-number="<?= $seat['numero_asiento'] ?>"
                                    <?= $disabled ?>>
                                <?= $seat['numero_asiento'] ?>
                            </button>
                            <?php 
                                else:
                                    echo '<div class="seat-spacer"></div>';
                                endif;
                                if ($col === 'C'): echo '<div class="aisle"></div>'; endif;
                            endforeach; 
                            ?>
                        </div>
                        <div class="row-number"><?= $row ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="selected-seats-summary" id="selectedSeatsSummary" style="display: none;">
                <h3>Asientos Seleccionados:</h3>
                <div id="selectedSeatsList"></div>
            </div>
            
            <div class="form-actions">
                <a href="<?= url('/booking/start?flight=' . $flight['id_vuelo']) ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <button type="submit" class="btn btn-primary btn-large" id="continueBtn" disabled>
                    <i class="fas fa-arrow-right"></i> Continuar
                </button>
            </div>
        </form>
    </div>
</div>

<script src="<?= asset('js/seat_selection.js') ?>"></script>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>
