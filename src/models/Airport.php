<?php
/**
 * Modelo de Aeropuerto
 * Sistema de Reserva de Vuelos
 */

class Airport {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Obtener todos los aeropuertos operativos
     * @return array
     */
    public function getAll() {
        try {
            $sql = "SELECT * FROM aeropuertos WHERE estado = 'operativo' ORDER BY ciudad ASC, nombre ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al obtener aeropuertos: " . $e->getMessage());
            }
            return [];
        }
    }
    
    /**
     * Obtener aeropuerto por ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        try {
            $sql = "SELECT * FROM aeropuertos WHERE id_aeropuerto = :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Buscar aeropuertos por ciudad
     * @param string $city
     * @return array
     */
    public function searchByCity($city) {
        try {
            $sql = "SELECT * FROM aeropuertos 
                    WHERE ciudad LIKE :city AND estado = 'operativo'
                    ORDER BY ciudad ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':city' => "%{$city}%"]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Obtener aeropuerto por código IATA
     * @param string $code
     * @return array|false
     */
    public function getByCode($code) {
        try {
            $sql = "SELECT * FROM aeropuertos WHERE codigo_iata = :code LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':code' => $code]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
}
