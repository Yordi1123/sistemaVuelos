<?php
/**
 * Controlador de Pagos - SIMPLIFICADO
 */

class PaymentController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Mostrar formulario de pago
     */
    public function checkout() {
        require_auth();
        
        $booking_id = get_param('booking');
        
        if (empty($booking_id)) {
            set_flash('error', 'No se especificó reserva');
            redirect(url('/profile/dashboard'));
            return;
        }
        
        // Obtener reserva - buscar por ID o por código
        $sql = "SELECT * FROM reservas WHERE id_reserva = :id OR codigo_reserva = :codigo LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $booking_id, ':codigo' => $booking_id]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$booking) {
            set_flash('error', 'Reserva no encontrada');
            redirect(url('/profile/dashboard'));
            return;
        }
        
        // Verificar que pertenezca al usuario
        $user = session_get('user');
        if ($booking['id_usuario'] != $user['id_usuario']) {
            set_flash('error', 'No tiene permiso para pagar esta reserva');
            redirect(url('/profile/dashboard'));
            return;
        }
        
        // Verificar que esté pendiente
        if ($booking['estado_reserva'] !== 'pendiente') {
            set_flash('error', 'Esta reserva ya fue procesada');
            redirect(url('/profile/dashboard'));
            return;
        }
        
        require VIEWS_PATH . '/payment/checkout.php';
    }
    
    /**
     * Procesar pago - SIMPLE
     */
    public function process() {
        require_auth();
        
        if (!is_post()) {
            redirect(url('/'));
            return;
        }
        
        $booking_id = post_param('booking_id');
        
        try {
            $this->db->beginTransaction();
            
            // Obtener reserva
            $sql = "SELECT * FROM reservas WHERE id_reserva = :id OR codigo_reserva = :codigo LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $booking_id, ':codigo' => $booking_id]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$booking) {
                throw new Exception('Reserva no encontrada');
            }
            
            // Actualizar estado de reserva a confirmada
            $sql_update = "UPDATE reservas SET estado_reserva = 'confirmada' WHERE id_reserva = :id";
            $stmt_update = $this->db->prepare($sql_update);
            $stmt_update->execute([':id' => $booking['id_reserva']]);
            
            // Actualizar asientos a ocupado
            $sql_asientos = "UPDATE asientos a
                            INNER JOIN asientos_reservados ar ON a.id_asiento = ar.id_asiento
                            INNER JOIN pasajeros p ON ar.id_pasajero = p.id_pasajero
                            SET a.estado = 'ocupado'
                            WHERE p.id_reserva = :id";
            $stmt_asientos = $this->db->prepare($sql_asientos);
            $stmt_asientos->execute([':id' => $booking['id_reserva']]);
            
            // Generar boletos para cada pasajero
            $sql_pasajeros = "SELECT p.id_pasajero, p.nombre, p.apellido, 
                                    ar.id_asiento, dr.id_detalle, dr.id_vuelo
                             FROM pasajeros p
                             INNER JOIN asientos_reservados ar ON p.id_pasajero = ar.id_pasajero
                             INNER JOIN detalle_reserva dr ON ar.id_detalle = dr.id_detalle
                             WHERE p.id_reserva = :id";
            $stmt_pasajeros = $this->db->prepare($sql_pasajeros);
            $stmt_pasajeros->execute([':id' => $booking['id_reserva']]);
            $pasajeros = $stmt_pasajeros->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($pasajeros as $pasajero) {
                $codigo_boleto = 'TKT' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
                
                $sql_boleto = "INSERT INTO boletos (id_reserva, id_pasajero, id_vuelo, id_asiento, codigo_boleto, estado_boleto, metodo_entrega)
                              VALUES (:reserva, :pasajero, :vuelo, :asiento, :codigo, 'emitido', 'electronico')";
                $stmt_boleto = $this->db->prepare($sql_boleto);
                $stmt_boleto->execute([
                    ':reserva' => $booking['id_reserva'],
                    ':pasajero' => $pasajero['id_pasajero'],
                    ':vuelo' => $pasajero['id_vuelo'],
                    ':asiento' => $pasajero['id_asiento'],
                    ':codigo' => $codigo_boleto
                ]);
            }
            
            $this->db->commit();
            
            set_flash('success', 'Pago procesado exitosamente. Reserva confirmada.');
            redirect(url('/payment/success?booking=' . $booking['codigo_reserva']));
            
        } catch (Exception $e) {
            $this->db->rollBack();
            set_flash('error', 'Error al procesar pago: ' . $e->getMessage());
            redirect(url('/payment/checkout?booking=' . $booking_id));
        }
    }
    
    /**
     * Página de éxito
     */
    public function success() {
        require_auth();
        
        $booking_code = get_param('booking');
        
        if (empty($booking_code)) {
            redirect(url('/'));
            return;
        }
        
        // Obtener reserva
        $sql = "SELECT * FROM reservas WHERE codigo_reserva = :codigo LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':codigo' => $booking_code]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);
        
        require VIEWS_PATH . '/payment/success.php';
    }
}
