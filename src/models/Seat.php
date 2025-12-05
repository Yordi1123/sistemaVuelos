<?php
/**
 * Modelo de Asiento
 * Sistema de Reserva de Vuelos
 */

class Seat {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Obtener asientos de un vuelo por categoría
     * @param int $flight_id
     * @param int $category_id
     * @return array
     */
    public function getByFlightAndCategory($flight_id, $category_id = null) {
        try {
            $sql = "SELECT a.*, c.nombre AS categoria
                    FROM asientos a
                    INNER JOIN categorias_asiento c ON a.id_categoria = c.id_categoria
                    WHERE a.id_vuelo = :flight_id";
            
            if ($category_id) {
                $sql .= " AND a.id_categoria = :category_id";
            }
            
            $sql .= " ORDER BY a.fila ASC, a.columna ASC";
            
            $stmt = $this->db->prepare($sql);
            $params = [':flight_id' => $flight_id];
            
            if ($category_id) {
                $params[':category_id'] = $category_id;
            }
            
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al obtener asientos: " . $e->getMessage());
            }
            return [];
        }
    }
    
    /**
     * Obtener asiento por ID
     * @param int $seat_id
     * @return array|false
     */
    public function getById($seat_id) {
        try {
            $sql = "SELECT * FROM asientos WHERE id_asiento = :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $seat_id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Verificar disponibilidad de asientos
     * @param array $seat_ids
     * @return bool
     */
    public function checkAvailability($seat_ids) {
        try {
            $placeholders = str_repeat('?,', count($seat_ids) - 1) . '?';
            $sql = "SELECT COUNT(*) FROM asientos 
                    WHERE id_asiento IN ($placeholders) 
                    AND estado = 'disponible'";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($seat_ids);
            
            return $stmt->fetchColumn() == count($seat_ids);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Reservar asientos
     * @param array $seat_ids
     * @param int $passenger_id
     * @param int $detail_id
     * @return bool
     */
    public function reserve($seat_ids, $passenger_id, $detail_id) {
        try {
            $this->db->beginTransaction();
            
            // Actualizar estado de asientos
            $placeholders = str_repeat('?,', count($seat_ids) - 1) . '?';
            $sql_update = "UPDATE asientos 
                          SET estado = 'reservado' 
                          WHERE id_asiento IN ($placeholders) 
                          AND estado = 'disponible'";
            
            $stmt_update = $this->db->prepare($sql_update);
            $stmt_update->execute($seat_ids);
            
            // Insertar en asientos_reservados
            $sql_insert = "INSERT INTO asientos_reservados 
                          (id_pasajero, id_asiento, id_detalle) 
                          VALUES (?, ?, ?)";
            
            $stmt_insert = $this->db->prepare($sql_insert);
            
            foreach ($seat_ids as $seat_id) {
                $stmt_insert->execute([$passenger_id, $seat_id, $detail_id]);
            }
            
            $this->db->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            if (DEBUG_MODE) {
                error_log("Error al reservar asientos: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Liberar asientos
     * @param array $seat_ids
     * @return bool
     */
    public function release($seat_ids) {
        try {
            $placeholders = str_repeat('?,', count($seat_ids) - 1) . '?';
            $sql = "UPDATE asientos 
                    SET estado = 'disponible' 
                    WHERE id_asiento IN ($placeholders)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($seat_ids);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Obtener mapa de asientos organizado
     * @param int $flight_id
     * @param int $category_id
     * @return array
     */
    public function getSeatMap($flight_id, $category_id) {
        $seats = $this->getByFlightAndCategory($flight_id, $category_id);
        
        $seatMap = [];
        foreach ($seats as $seat) {
            $row = $seat['fila'];
            if (!isset($seatMap[$row])) {
                $seatMap[$row] = [];
            }
            $seatMap[$row][$seat['columna']] = $seat;
        }
        
        ksort($seatMap);
        return $seatMap;
    }
}
