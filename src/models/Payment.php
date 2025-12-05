<?php
/**
 * Modelo de Pago
 * Sistema de Reserva de Vuelos
 */

class Payment {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Crear pago
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO pagos 
                    (id_reserva, metodo_pago, numero_tarjeta_enmascarado, 
                     monto, estado_pago, codigo_transaccion)
                    VALUES 
                    (:reserva, :metodo, :tarjeta, :monto, :estado, :codigo)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':reserva' => $data['id_reserva'],
                ':metodo' => $data['metodo_pago'],
                ':tarjeta' => $data['numero_tarjeta_enmascarado'] ?? null,
                ':monto' => $data['monto'],
                ':estado' => $data['estado_pago'] ?? 'completado',
                ':codigo' => $data['codigo_transaccion']
            ]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al crear pago: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Obtener pagos de una reserva
     */
    public function getByReservation($reserva_id) {
        try {
            $sql = "SELECT * FROM pagos WHERE id_reserva = :id ORDER BY fecha_pago DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $reserva_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Simular procesamiento de pago
     */
    public function processPayment($reserva_id, $payment_data) {
        try {
            $this->db->beginTransaction();
            
            // Generar código de transacción
            $codigo_transaccion = 'TXN' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
            
            // Simular validación de tarjeta (siempre exitoso en demo)
            $card_number = $payment_data['card_number'];
            $masked_card = '****' . substr($card_number, -4);
            
            // Crear registro de pago
            $pago_data = [
                'id_reserva' => $reserva_id,
                'metodo_pago' => $payment_data['payment_method'] ?? 'tarjeta_credito',
                'numero_tarjeta_enmascarado' => $masked_card,
                'monto' => $payment_data['amount'],
                'estado_pago' => 'completado',
                'codigo_transaccion' => $codigo_transaccion
            ];
            
            $pago_id = $this->create($pago_data);
            
            if (!$pago_id) {
                throw new Exception('Error al registrar pago');
            }
            
            // Actualizar estado de reserva a confirmada
            $sql = "UPDATE reservas SET estado_reserva = 'confirmada' WHERE id_reserva = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $reserva_id]);
            
            // Generar boletos
            $this->generateTickets($reserva_id);
            
            $this->db->commit();
            
            return [
                'success' => true,
                'pago_id' => $pago_id,
                'codigo_transaccion' => $codigo_transaccion
            ];
            
        } catch (Exception $e) {
            $this->db->rollBack();
            if (DEBUG_MODE) {
                error_log("Error al procesar pago: " . $e->getMessage());
            }
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Generar boletos para una reserva
     */
    private function generateTickets($reserva_id) {
        // Obtener pasajeros de la reserva
        $sql = "SELECT p.*, dr.id_detalle 
                FROM pasajeros p
                INNER JOIN reservas r ON p.id_reserva = r.id_reserva
                INNER JOIN detalle_reserva dr ON r.id_reserva = dr.id_reserva
                WHERE p.id_reserva = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $reserva_id]);
        $pasajeros = $stmt->fetchAll();
        
        foreach ($pasajeros as $pasajero) {
            $codigo_boleto = 'TKT' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
            
            $sql_ticket = "INSERT INTO boletos 
                          (id_pasajero, id_detalle, codigo_boleto, estado_boleto, metodo_entrega)
                          VALUES 
                          (:pasajero, :detalle, :codigo, 'emitido', 'email')";
            
            $stmt_ticket = $this->db->prepare($sql_ticket);
            $stmt_ticket->execute([
                ':pasajero' => $pasajero['id_pasajero'],
                ':detalle' => $pasajero['id_detalle'],
                ':codigo' => $codigo_boleto
            ]);
        }
    }
}
