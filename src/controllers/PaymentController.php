<?php
/**
 * Controlador de Pagos
 */

class PaymentController {
    private $paymentModel;
    private $bookingModel;
    
    public function __construct() {
        $this->paymentModel = new Payment();
        $this->bookingModel = new Booking();
    }
    
    /**
     * Mostrar formulario de pago
     */
    public function checkout() {
        require_auth();
        
        $booking_code = get_param('booking');
        
        if (empty($booking_code)) {
            set_flash('error', 'Código de reserva no proporcionado');
            redirect(url('/'));
            return;
        }
        
        if (DEBUG_MODE) {
            error_log("Buscando reserva con código: " . $booking_code);
        }
        
        $booking = $this->bookingModel->getByCode($booking_code);
        
        if (DEBUG_MODE) {
            error_log("Resultado de búsqueda: " . print_r($booking, true));
        }
        
        if (!$booking) {
            set_flash('error', 'Reserva no encontrada. Código: ' . $booking_code);
            redirect(url('/'));
            return;
        }
        
        // Verificar que la reserva pertenezca al usuario
        $user = session_get('user');
        if ($booking['id_usuario'] != $user['id_usuario']) {
            set_flash('error', 'No tiene permiso para pagar esta reserva');
            redirect(url('/'));
            return;
        }
        
        // Verificar que la reserva esté pendiente
        if ($booking['estado_reserva'] !== 'pendiente') {
            set_flash('error', 'Esta reserva ya ha sido procesada');
            redirect(url('/'));
            return;
        }
        
        require VIEWS_PATH . '/payment/checkout.php';
    }
    
    /**
     * Procesar pago
     */
    public function process() {
        require_auth();
        
        if (!is_post()) {
            redirect(url('/'));
            return;
        }
        
        $booking_id = post_param('booking_id');
        $card_number = post_param('card_number');
        $card_name = post_param('card_name');
        $card_expiry = post_param('card_expiry');
        $card_cvv = post_param('card_cvv');
        $amount = post_param('amount');
        
        // Validaciones básicas
        $errors = [];
        
        if (empty($card_number) || strlen($card_number) < 13) {
            $errors[] = 'Número de tarjeta inválido';
        }
        
        if (empty($card_name)) {
            $errors[] = 'Nombre del titular requerido';
        }
        
        if (empty($card_expiry)) {
            $errors[] = 'Fecha de expiración requerida';
        }
        
        if (empty($card_cvv) || strlen($card_cvv) < 3) {
            $errors[] = 'CVV inválido';
        }
        
        if (!empty($errors)) {
            set_flash('error', implode(', ', $errors));
            redirect(url('/payment/checkout?booking=' . $booking_id));
            return;
        }
        
        // Procesar pago
        $payment_data = [
            'card_number' => $card_number,
            'card_name' => $card_name,
            'amount' => $amount,
            'payment_method' => 'tarjeta_credito'
        ];
        
        $result = $this->paymentModel->processPayment($booking_id, $payment_data);
        
        if ($result['success']) {
            set_flash('success', 'Pago procesado exitosamente');
            redirect(url('/payment/success?transaction=' . $result['codigo_transaccion']));
        } else {
            set_flash('error', 'Error al procesar el pago: ' . $result['error']);
            redirect(url('/payment/checkout?booking=' . $booking_id));
        }
    }
    
    /**
     * Página de éxito
     */
    public function success() {
        require_auth();
        
        $transaction_code = get_param('transaction');
        
        if (empty($transaction_code)) {
            redirect(url('/'));
            return;
        }
        
        require VIEWS_PATH . '/payment/success.php';
    }
}
