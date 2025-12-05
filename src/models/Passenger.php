<?php
/**
 * Modelo de Pasajero
 * Sistema de Reserva de Vuelos
 */

class Passenger {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Crear pasajero
     * @param array $data
     * @return int|false ID del pasajero creado
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO pasajeros 
                    (id_reserva, nombre, apellido, tipo_documento, numero_documento, 
                     fecha_nacimiento, genero, nacionalidad, email, telefono, tipo_pasajero)
                    VALUES 
                    (:reserva, :nombre, :apellido, :tipo_doc, :num_doc, 
                     :fecha_nac, :genero, :nacionalidad, :email, :telefono, :tipo_pasajero)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':reserva' => $data['id_reserva'],
                ':nombre' => $data['nombre'],
                ':apellido' => $data['apellido'],
                ':tipo_doc' => $data['tipo_documento'] ?? 'DNI',
                ':num_doc' => $data['numero_documento'],
                ':fecha_nac' => $data['fecha_nacimiento'] ?? null,
                ':genero' => $data['genero'] ?? null,
                ':nacionalidad' => $data['nacionalidad'] ?? 'Peruana',
                ':email' => $data['email'] ?? null,
                ':telefono' => $data['telefono'] ?? null,
                ':tipo_pasajero' => $data['tipo_pasajero'] ?? 'adulto'
            ]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al crear pasajero: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Obtener pasajeros de una reserva
     * @param int $reserva_id
     * @return array
     */
    public function getByReservation($reserva_id) {
        try {
            $sql = "SELECT p.*, 
                           GROUP_CONCAT(a.numero_asiento ORDER BY a.numero_asiento) AS asientos
                    FROM pasajeros p
                    LEFT JOIN asientos_reservados ar ON p.id_pasajero = ar.id_pasajero
                    LEFT JOIN asientos a ON ar.id_asiento = a.id_asiento
                    WHERE p.id_reserva = :reserva_id
                    GROUP BY p.id_pasajero
                    ORDER BY p.id_pasajero ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':reserva_id' => $reserva_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Obtener pasajero por ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        try {
            $sql = "SELECT * FROM pasajeros WHERE id_pasajero = :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Actualizar pasajero
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        try {
            $sql = "UPDATE pasajeros 
                    SET nombre = :nombre, 
                        apellido = :apellido,
                        tipo_documento = :tipo_doc,
                        numero_documento = :num_doc,
                        fecha_nacimiento = :fecha_nac,
                        genero = :genero,
                        nacionalidad = :nacionalidad,
                        email = :email,
                        telefono = :telefono
                    WHERE id_pasajero = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':nombre' => $data['nombre'],
                ':apellido' => $data['apellido'],
                ':tipo_doc' => $data['tipo_documento'],
                ':num_doc' => $data['numero_documento'],
                ':fecha_nac' => $data['fecha_nacimiento'] ?? null,
                ':genero' => $data['genero'] ?? null,
                ':nacionalidad' => $data['nacionalidad'] ?? 'Peruana',
                ':email' => $data['email'] ?? null,
                ':telefono' => $data['telefono'] ?? null,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Eliminar pasajero
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM pasajeros WHERE id_pasajero = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
