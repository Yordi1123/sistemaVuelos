<?php
/**
 * Modelo de Aerolínea
 * Sistema de Reserva de Vuelos
 */

class Airline {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Obtener todas las aerolíneas activas
     * @return array
     */
    public function getAll() {
        try {
            $sql = "SELECT * FROM aerolineas WHERE estado = 'activa' ORDER BY nombre ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al obtener aerolíneas: " . $e->getMessage());
            }
            return [];
        }
    }
    
    /**
     * Obtener aerolínea por ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        try {
            $sql = "SELECT * FROM aerolineas WHERE id_aerolinea = :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Obtener aerolínea por código IATA
     * @param string $code
     * @return array|false
     */
    public function getByCode($code) {
        try {
            $sql = "SELECT * FROM aerolineas WHERE codigo_iata = :code LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':code' => $code]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
}
