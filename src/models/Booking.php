<?php
/**
 * Modelo de Reserva
 * Sistema de Reserva de Vuelos
 */

class Booking {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Crear nueva reserva
     * @param array $data
     * @return int|false ID de reserva creado
     */
    public function create($data) {
        try {
            // Generar código de reserva único
            $codigo_reserva = $this->generateReservationCode();
            
            // Calcular fecha de expiración (24 horas)
            $fecha_expiracion = date('Y-m-d H:i:s', strtotime('+24 hours'));
            
            $sql = "INSERT INTO reservas 
                    (codigo_reserva, id_usuario, estado_reserva, fecha_expiracion, 
                     total_pasajeros, monto_total, observaciones)
                    VALUES 
                    (:codigo, :usuario, 'pendiente', :expiracion, :pasajeros, :monto, :obs)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':codigo' => $codigo_reserva,
                ':usuario' => $data['id_usuario'],
                ':expiracion' => $fecha_expiracion,
                ':pasajeros' => $data['total_pasajeros'],
                ':monto' => $data['monto_total'],
                ':obs' => $data['observaciones'] ?? null
            ]);
            
            $reserva_id = $this->db->lastInsertId();
            
            // Insertar detalle de reserva (vuelo)
            $sql_detalle = "INSERT INTO detalle_reserva 
                           (id_reserva, id_vuelo, orden_vuelo, id_tarifa, cantidad_pasajeros, subtotal)
                           VALUES 
                           (:reserva, :vuelo, 1, :tarifa, :pasajeros, :subtotal)";
            
            $stmt_detalle = $this->db->prepare($sql_detalle);
            $stmt_detalle->execute([
                ':reserva' => $reserva_id,
                ':vuelo' => $data['id_vuelo'],
                ':tarifa' => $data['id_tarifa'],
                ':pasajeros' => $data['total_pasajeros'],
                ':subtotal' => $data['monto_total']
            ]);
            
            return $reserva_id;
            
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al crear reserva: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Generar código de reserva único
     * @return string
     */
    private function generateReservationCode() {
        do {
            $code = 'RV' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
            $exists = $this->reservationCodeExists($code);
        } while ($exists);
        
        return $code;
    }
    
    /**
     * Verificar si código de reserva existe
     * @param string $code
     * @return bool
     */
    private function reservationCodeExists($code) {
        $sql = "SELECT COUNT(*) FROM reservas WHERE codigo_reserva = :code";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':code' => $code]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Obtener reserva por ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        try {
            $sql = "SELECT r.*, u.nombre, u.apellido, u.email
                    FROM reservas r
                    INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
                    WHERE r.id_reserva = :id
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (DEBUG_MODE) {
                error_log("getById($id) SQL: " . $sql);
                error_log("getById($id) Result: " . print_r($result, true));
            }
            
            return $result;
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error en getById: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Obtener reserva por código
     * @param string $code
     * @return array|false
     */
    public function getByCode($code) {
        try {
            $sql = "SELECT * FROM reservas WHERE codigo_reserva = :code LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':code' => $code]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Obtener detalles de reserva (vuelos)
     * @param int $reserva_id
     * @return array
     */
    public function getDetails($reserva_id) {
        try {
            $sql = "SELECT dr.*, v.numero_vuelo, v.fecha_salida, v.fecha_llegada,
                           a.nombre AS aerolinea,
                           ao.ciudad AS ciudad_origen, ao.codigo_iata AS codigo_origen,
                           ad.ciudad AS ciudad_destino, ad.codigo_iata AS codigo_destino,
                           tf.precio, c.nombre AS categoria
                    FROM detalle_reserva dr
                    INNER JOIN vuelos v ON dr.id_vuelo = v.id_vuelo
                    INNER JOIN aerolineas a ON v.id_aerolinea = a.id_aerolinea
                    INNER JOIN aeropuertos ao ON v.id_aeropuerto_origen = ao.id_aeropuerto
                    INNER JOIN aeropuertos ad ON v.id_aeropuerto_destino = ad.id_aeropuerto
                    INNER JOIN tarifas_vuelo tf ON dr.id_tarifa = tf.id_tarifa
                    INNER JOIN categorias_asiento c ON tf.id_categoria = c.id_categoria
                    WHERE dr.id_reserva = :id
                    ORDER BY dr.orden_vuelo ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $reserva_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Obtener reservas de un usuario
     * @param int $user_id
     * @return array
     */
    public function getUserBookings($user_id) {
        try {
            $sql = "SELECT r.*, 
                           COUNT(DISTINCT dr.id_vuelo) AS cantidad_vuelos,
                           MIN(v.fecha_salida) AS fecha_primer_vuelo
                    FROM reservas r
                    LEFT JOIN detalle_reserva dr ON r.id_reserva = dr.id_reserva
                    LEFT JOIN vuelos v ON dr.id_vuelo = v.id_vuelo
                    WHERE r.id_usuario = :user_id
                    GROUP BY r.id_reserva
                    ORDER BY r.fecha_reserva DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $user_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Actualizar estado de reserva
     * @param int $reserva_id
     * @param string $estado
     * @return bool
     */
    public function updateStatus($reserva_id, $estado) {
        try {
            $sql = "UPDATE reservas SET estado_reserva = :estado WHERE id_reserva = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':estado' => $estado,
                ':id' => $reserva_id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Cancelar reserva
     * @param int $reserva_id
     * @return bool
     */
    public function cancel($reserva_id) {
        try {
            $this->db->beginTransaction();
            
            // Actualizar estado de reserva
            $this->updateStatus($reserva_id, RESERVATION_STATUS_CANCELLED);
            
            // Liberar asientos reservados
            $sql = "UPDATE asientos a
                    INNER JOIN asientos_reservados ar ON a.id_asiento = ar.id_asiento
                    INNER JOIN pasajeros p ON ar.id_pasajero = p.id_pasajero
                    SET a.estado = 'disponible'
                    WHERE p.id_reserva = :reserva_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':reserva_id' => $reserva_id]);
            
            $this->db->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
